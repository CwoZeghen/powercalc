<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|array',
            'name.*' => 'required|unique:addons,name',
            'description' => 'required|array',
            'description.*' => 'required|max:300',
            'cost' => 'required|array',
            'cost.*' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();

        $name = $data['name'];
        $cost = $data['cost'];
        $description = $data['description'];

        foreach ($name as $k => $el) {
            if (!Addon::where('name', $el)->first()) {
                Addon::create(['name' => $el, 'cost' => $cost[$k], 'description' => $description[$k]]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Addons has been saved"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Addon $addon)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|exists:addons',
            'name' => 'required|string|unique:addons,name,' . $addon->id,
            'cost' => 'required|numeric',
            'description' => 'required|max:300',
        ]);
        if ($validator->fails()) {
            $m = implode("<br>", $validator->errors()->all());
            return response()->json([
                'success' => false,
                'message' => $m
            ]);
        }
        $data = $validator->validated();
        $addon->update($data);

        return response()->json([
            'success' => true,
            'message' => "Addon has been updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Addon $addon)
    {
        $addon->delete();
        return response()->json([
            'success' => true,
            'message' => "Addon has been deleted"
        ]);
    }
}
