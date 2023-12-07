<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Appliance;
use App\Models\Appliance2;
use App\Models\Appsetting;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class AdminController extends Controller
{
    public function home()
    {
        $dump = ['Blender' => 500, 'Can Opener' => 150, 'Coffee Machine' => 1000, 'Dishwasher' => 1500, 'Espresso Machine' => 800, 'Freezer - Upright - 15 cu. ft.' => 310, 'Freezer - Chest - 15 cu. ft.' => 270, 'Fridge - 20 cu. ft. (AC)' => 353, 'Fridge -16 cu. ft. (AC)' => 300, 'Garbage Disposal' => 450, 'Kettle - Electric' => 1200, 'Microwave' => 1000, 'Oven - Electric' => 1200, 'Toaster' => 850, 'Toaster Oven' => 1200, 'Stand Mixer' => 300, 'Box Fan' => 200, 'Ceiling Fan' => 120, 'Central Air Conditioner - 24,000 BTU NA' => 3800, 'Central Air Conditioner - 10,000 BTU NA' => 3250, 'Furnace Fan Blower' => 800, 'Space Heater NA' => 1500, 'Tankless Water Heater - Electric' => 1500, 'Water Heater - Electric' => 4500, 'Window Air Conditioner 10,000 BTU NA' => 900, 'Window Air Conditioner 12,000 BTU NA' => 1200, 'Vacuum' => 1000, 'Clothes Dryer - Electric' => 3000, 'Clothes Dryer - Gas' => 1800, 'Clothes Washer' => 800, 'Bluray Player' => 15, 'Cable Box' => 35, 'DVD Player' => 15, 'TV - LCD' => 150, 'TV - Plasma' => 200, 'Satellite Dish' => 25, 'Stereo Receiver' => 450, 'Video Game Console' => 150, 'CFL Bulb - 40 Watt Equivalent' => 11, 'CFL Bulb - 60 Watt Equivalent' => 18, 'CFL Bulb - 75 Watt Equivalent' => 20, 'CFL Bulb - 100 Watt Equivalent' => 30, 'Compact Fluorescent 20 Watt' => 22, 'Compact Fluorescent 25 Watt' => 28, 'Halogen - 40 Watt' => 40, 'Incandescent 50 Watt' => 50, 'Incandescent 100 Watt' => 100, 'LED Bulb - 40 Watt Equivalent' => 10, 'LED Bulb - 60 Watt Equivalent' => 13, 'LED Bulb - 75 watt equivalent' => 18, 'LED Bulb - 100 Watt Equivalent' => 23, 'Desktop Computer (Standard)' => 200, 'Desktop Computer (Gaming)' => 500, 'Laptop' => 100, 'LCD Monitor' => 100, 'Modem' => 7, 'Printer' => 100, 'Router' => 7, 'Smart Phone- Recharge' => 6, 'Tablet- Recharge' => 8, 'Band Saw - 14"' => 1100, 'Belt Sander - 3"' => 1000, 'Chain Saw - 12"' => 1100, 'Circular Saw - 7-1/4"' => 900, 'Circular Saw 8-1/4"' => 1400, 'Disc Sander - 9"' => 1200, 'Drill - 1/4"' => 250, 'Drill - 1/2"' => 750, 'Drill - 1"' => 100, 'Hedge Trimmer' => 450, 'Weed Eater' => 500, 'Clock Radio' => 7, 'Curling Iron' => 150, 'Dehumidifier' => 280, 'Electric Shaver' => 15, 'Electric Blanket' => 200, 'Hair Dryer' => 1500, 'Humidifier' => 200, 'Radiotelephone - Receive' => 5, 'Radiotelephone - Transmit' => 75, 'Sewing Machine' => 100, 'Well Pump - 1/3 1HP' => 1000, 'Well Pump - 3 HP' => 2500, 'Well Pump - 5 HP' => 4500];
        foreach ($dump as $k => $v) {
            $e =  Appliance::where(['name' => $k])->first();
            if (!$e) {
                Appliance::create(['name' => $k, 'watts' => $v]);
            }
        }
        $a = [
            ['name' => 'Remote support', 'cost' => 50, 'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident aspernatur ipsum quis sit quaerat. Accusamus, totam illo, consequatur eum aperiam laboriosam dolorem a hic rerum dolore possimus labore eveniet omnis?'],
            ['name' => 'Repair', 'cost' => 120, 'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident aspernatur ipsum quis sit quaerat. Accusamus, totam illo, consequatur eum aperiam laboriosam dolorem a hic rerum dolore possimus labore eveniet omnis?'],
            ['name' => 'Maintenance', 'cost' => 200, 'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident aspernatur ipsum quis sit quaerat. Accusamus, totam illo, consequatur eum aperiam laboriosam dolorem a hic rerum dolore possimus labore eveniet omnis?'],
        ];
        foreach ($a as $v) {
            Addon::create($v);
        }
        $data = Appliance::orderBy('id', 'desc')->get();
        $data2 = Appliance2::orderBy('id', 'desc')->get();
        return view('admin.home', compact('data', 'data2'));
    }

    public function contact()
    {
        $data = Contact::orderBy('id', 'desc')->get();
        $appliances = Appliance::orderBy('id', 'desc')->get();
        $items = Appliance::orderBy('id', 'desc')->get(['id', 'name as item', 'watts']);
        $tab = [];
        foreach ($items as $item) {
            $tab[] = (object) ['id' => $item->id, 'item' => $item->item, 'watts' => $item->watts];
        }
        $items = json_encode($tab);

        $set = Appsetting::first();
        $cost = (float) @$set->cost;
        $peakload = (float) @$set->peakload;

        return view('admin.contact', compact('data', 'appliances', 'items', 'cost', 'peakload'));
    }

    public function addons()
    {
        $data = Addon::orderBy('id', 'desc')->get();
        return view('admin.addons', compact('data'));
    }

    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        $m = 'Your are now connected';
        $success = false;
        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
        } else {
            $data = $validator->validated();
            $u = User::first();
            if (!$u) {
                User::create(['name' => 'admin', 'email' => 'admin@admin.admin', 'password' => Hash::make('admin')]);
            }
            if (!Auth::attempt($data)) {
                $m = "Invalid email or password";
            } else {
                $success = true;
            }
        }

        $data = [
            'success' => $success,
            'message'  => $m
        ];
        if ($success) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $data['token'] = $token;
        }
        return response()->json($data);
    }

    public function make_pdf()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
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
            abort(400, $m);
        }

        $data = $validator->validated();
        $mpdf = buildPDF($data);
        $mpdf->Output('PowerCalculatorResult.pdf', 'D');
    }

    public function getpdf($item = null)
    {
        if (!$item or !($e = Contact::where('id', $item)->first())) {
            abort(404, 'No PDF found');
        }

        $d = (object) json_decode($e->appliance);
        $addons = (array) @$d->addons;
        $image = (array) @$d->image;
        $appliance = (array) @$d->appliance;
        $watts = (array) @$d->watts;
        $qty = (array) @$d->qty;
        $onperday = (array) @$d->onperday;
        $email = @$d->email;

        $data = compact('email', 'addons', 'image', 'appliance',  'watts', 'qty', 'onperday');
        $mpdf = buildPDF($data);
        $n = str_replace(' ', '_', $e->name);
        $mpdf->Output("$n\_PowerCalculatorResult.pdf", 'D');
    }
    public function contact_edit(Contact $id)
    {
        $set = Appsetting::first();
        $cost = (float) @$set->cost;
        $peakload = (float) @$set->peakload;

        $appl = $id;
        $appliances = Appliance::orderBy('id', 'desc')->get();
        $addons = Addon::orderBy('id', 'desc')->get();
        return view('admin.contact_edit', compact('appl', 'appliances', 'cost', 'peakload', 'addons'));
    }
    public function mail_verify($token)
    {
        $el = Contact::where('token', $token)->first();
        if ($el) {
            $el->update(['email_verified_at' => now()]);
            $address = json_decode($el->address);
            $lead = [
                'name' => $el->name,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $address->country,
                'address' => $address->address,
                'email' => $el->email,
                'mobile' => $el->phone
            ];
            sendToCRM($lead);
            Session::flash('_message', 'Your email has been verified');
        } else {
            Session::flash('_message', 'The submitted url seems to be invalid');
        }
        return redirect(route('home', '#power-calculator'));
    }

    public function embeded_power_calc()
    {

        $cost = (float) @Appsetting::first()->cost;
        $peakload = (float) @Appsetting::first()->peakload;
        $col = ['id', 'name as item', 'watts'];
        $items = Appliance::orderBy('id', 'desc')->get($col);
        $tab = [];
        foreach ($items as $item) {
            $tab[] = (object) ['id' => $item->id, 'item' => $item->item, 'watts' => $item->watts];
        }
        $items = json_encode($tab);
        $addons = Addon::orderBy('id', 'desc')->get();
        return view('embeded', compact('cost', 'peakload', 'items', 'addons'));
    }
}
