<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerify;
use App\Models\Addon;
use App\Models\Appliance;
use App\Models\Appliance2;
use App\Models\Appsetting;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function new_request()
    {
        $a = !request()->appliance;
        $b = !request()->qty;
        $c = !request()->watts;
        $d = !request()->onperday;

        if ($a or $b or $c or $d) {
            return response()->json([
                'success' => false,
                'message' => "Please provide your appliance informations"
            ]);
        }

        $validator = Validator::make(request()->all(), [
            'image' => 'required|array',
            'image.*' => 'required',
            'appliance' => 'required|array',
            'appliance.*' => 'required',
            'qty' => 'required|array',
            'qty.*' => 'numeric',
            'watts' => 'required|array',
            'watts.*' => 'numeric',
            'onperday' => 'required|array',
            'onperday.*' => 'numeric',
            'addons' => 'sometimes|array',
            'addons.*' => 'exists:addons,id',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();

        $cost = (float) @Appsetting::first()->cost;
        $data1 = $data;
        $data1['cost'] = $cost;

        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|regex:/(\+)[0-9]{10}/',
            'address' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }

        $address = request()->address;
        $address = json_decode($address);
        if (!is_object($address)) {
            $m = "The provided address seem to be invalid";
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $address->lat = request()->lat;
        $address->lon = request()->lon;

        if (!empty($email) and strlen($email) > 50) {
            return response()->json([
                'success' => false,
                'message' => "invalid email"
            ]);
        }
        if (!empty($phone) and strlen($phone) > 50) {
            return response()->json([
                'success' => false,
                'message' => "invalid phone number"
            ]);
        }

        $addons = (array) @$data1['addons'];
        $t = [];
        $ao = Addon::whereIn('id', $addons)->get();
        foreach ($ao as $addon) {
            $t[] = (object) ['id' => $addon->id, 'name' => $addon->name, 'cost' => $addon->cost, 'description' => $addon->description];
        }
        $data1['addons'] = $t;

        $data = $validator->validated();
        $data['appliance'] = json_encode($data1);
        $data['address'] = json_encode($address);

        $lastId = request()->item;
        $mustverify = false;

        DB::beginTransaction();
        if ($lastId) {
            $e = Contact::where('id', $lastId)->first();
            if ($e) {
                $oldemail = $e->email;
                $e->update($data);
            }
            $id = $lastId;
        } else {
            $data['token'] = token();
            $e = Contact::create($data);
            $id = $e->id;
            $mustverify = true;
        }

        $em = $data['email'];

        if ($em != @$oldemail and $e) {
            $mustverify = true;
            $e->update(['email_verified_at' => NULL]);
            $data['token'] = $e->token;
        }

        $test = Contact::where(['email' => $em])->whereNotNull('email_verified_at')->first();
        if ($test) {
            $mustverify = false;
            $e->update(['email_verified_at' => $test->email_verified_at]);
            $skip = true;
        }

        $success = true;
        if ($mustverify) {
            try {
                Mail::to($data['email'])->send(new EmailVerify($data));
                $m = "Your information has been sent. Please verify your mail box to verfy your email address";
                DB::commit();
            } catch (\Throwable $th) {
                $success = false;
                $m = "Sorry we are anable to verify your email address, please retry.";
            }
        } else {
            if ($e->email_verified_at or isset($skip)) {
                $m = "Your information has been sent. THANKS";
                DB::commit();
                $link = route('getpdf', $e->id);
                $lead = [
                    'name' => $data['name'],
                    'city' => $address->city,
                    'state' => $address->state,
                    'country' => $address->country,
                    'address' => $address->address,
                    'email' => $data['email'],
                    'mobile' => $data['phone'],
                    'website' => $link,
                ];
                sendToCRM($lead);
            } else {
                $m = "Sorry, you must verify your email address! please check the link send to your email address";
                $success = false;
                try {
                    $data['token'] = $e->token;
                    Mail::to($data['email'])->send(new EmailVerify($data));
                } catch (\Throwable $th) {
                }
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $m,
            'item' => $id
        ]);
    }

    public function contact_update()
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|exists:contact',
            'image' => 'required|array',
            'image.*' => 'required',
            'appliance' => 'required|array',
            'appliance.*' => 'required',
            'qty' => 'required|array',
            'qty.*' => 'numeric',
            'watts' => 'required|array',
            'watts.*' => 'numeric',
            'onperday' => 'required|array',
            'onperday.*' => 'numeric',
            'addons' => 'sometimes|array',
            'addons.*' => 'exists:addons,id',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();
        $cost = (float) @Appsetting::first()->cost;
        $data['cost'] = $cost;

        $addons = (array) @$data['addons'];
        $t = [];
        $ao = Addon::whereIn('id', $addons)->get();
        foreach ($ao as $addon) {
            $t[] = (object) ['id' => $addon->id, 'name' => $addon->name, 'cost' => $addon->cost, 'description' => $addon->description];
        }
        $data['addons'] = $t;

        $id = $data['id'];
        unset($data['id']);
        $d['appliance'] = json_encode($data);

        $e =  Contact::where('id', $id)->first();
        if ($e) {
            $e->update($d);
        }
        return response()->json([
            'success' => true,
            'message' => "Informations has been updated successfully"
        ]);
    }

    public function save_appliance()
    {
        $validator = Validator::make(request()->all(), [
            'appliance' => 'required|array',
            'appliance.*' => 'required|unique:appliance,name',
            'watts' => 'required|array',
            'watts.*' => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();

        $appliance = $data['appliance'];
        $watts = $data['watts'];

        foreach ($appliance as $k => $el) {
            if (!Appliance::where('name', $el)->first()) {
                Appliance::create(['name' => $el, 'watts' => $watts[$k]]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Appliances has been saved"
        ]);
    }

    public function update_appliance()
    {
        $type = request()->type;
        if ($type == 'notlisted') {
            $validator = Validator::make(request()->all(), [
                'id' => 'required|exists:appliance2'
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'id' => 'required|exists:appliance'
            ]);
        }

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        if ($type == 'notlisted') {
            $app = Appliance2::where('id', request()->id)->first();
        } else {
            $app = Appliance::where('id', request()->id)->first();
        }

        if ($type == 'notlisted') {
            $validator = Validator::make(request()->all(), [
                'id' => 'required|exists:appliance2',
                'name' => 'required|string|',
                'watts' => 'required|numeric|min:0.001',
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'id' => 'required|exists:appliance',
                'name' => 'required|string|unique:appliance,name,' . $app->id,
                'watts' => 'required|numeric|min:0.001',
            ]);
        }

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();
        $app->update($data);

        return response()->json([
            'success' => true,
            'message' => "Appliances has been updated"
        ]);
    }

    public function save_appliance2()
    {
        $id = request()->id;
        $success = false;
        $msg = '';
        if ($id) {
            $t = Appliance2::where('id', $id)->first();
            if ($t) {
                $t2 = Appliance::where('name', $t->name)->first();
                if (!$t2) {
                    Appliance::create(['name' => $t->name, 'watts' => (float) $t->watts]);
                    $t->delete();
                    $success = true;
                    $msg = 'Appliance created successfully';
                } else {
                    $msg = "This appliance already exists, please delete or ignore it";
                }
            }
        }

        return response(['success' => $success, 'message' => $msg]);
    }

    public function delete_appliance()
    {
        $id = request()->id;
        $type = request()->type;
        if ($type == 'notlisted') {
            Appliance2::where('id', $id)->delete();
        } else {
            Appliance::where('id', $id)->delete();
        }
        return response()->json([
            'message' => "Appliance deleted"
        ]);
    }

    public function save_cost()
    {
        $validator = Validator::make(request()->all(), [
            'cost' => 'required|numeric|min:0.01',
            'peakload' => 'required|numeric|min:0.01|max:1',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();
        $set = Appsetting::first();
        if ($set) {
            $set->update($data);
        } else {
            Appsetting::create($data);
        }
        return response()->json([
            'success' => true,
            'message' => "Settings updated successfully"
        ]);
    }

    public function not_listed()
    {
        $data = json_decode(request()->data);
        if (is_array($data)) {
            foreach ($data as $el) {
                if (@$el->item and @$el->watts) {
                    $t = Appliance2::where('name', $el->item)->first();
                    if (!$t) {
                        Appliance2::create(['name' => $el->item, 'watts' => (float) $el->watts]);
                    }
                }
            }
        }
    }
}
