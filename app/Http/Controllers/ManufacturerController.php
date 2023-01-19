<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Models\Part;


class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //page display handled within the react app
        return view('manufacturers')->with('manufacturers', Manufacturer::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //page display handled within the react app
        return view('react');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newManufacturer = new Manufacturer();

        $newManufacturer ->name = $request->input('name');
        $newManufacturer ->parts_on_sale = 0;

        $issaved = $newManufacturer ->save();

        return $issaved ? "success" : "failure";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) //if no id show all
    {
        return View('manufacturerEditView')->
        with('manufacturer', Manufacturer::where('id', $id)->first());
        // if(isset($id) && is_Numeric($id)) {
        //     return Manufacturer::where('id', $id)->first();
        // } else {
        //     return Manufacturer::all();
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return View('manufacturerEditView')->with('manufacturer', Manufacturer::where('id', $id)->first())->with('state', 'edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existingManufacturer = Manufacturer::where('id', $id)->first();
        if(!$existingManufacturer) {
            return "failure";
        }

        //special cases
        $name = $request->input('name');
        if($name) {
            $existingManufacturer->setAttribute('name', $name);
        }

        $partsCount = $request->input('parts_on_sale');
        if($partsCount) {
            $existingManufacturer->setAttribute('parts_on_sale', $partsCount);
        }

        $issaved =  $existingManufacturer->save();
    
        return $issaved ? "success" : "failure";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingManufacturer = Manufacturer::where('id', $id)->first();
        if($existingManufacturer && Part::where('manufacturer_id', $id)->count() > 0) {
            $deletedParts = Part::where('manufacturer_id', $id)->delete();
            $isdestroyed = $existingManufacturer->delete();
            return $isdestroyed && $deletedParts ? "success: manufacturer and parts deleted" : "failure: manufacturer and parts not deleted";
        }

        $isdestroyed = $existingManufacturer->delete();
        return $isdestroyed ? "success" : "failure";
    }
}
