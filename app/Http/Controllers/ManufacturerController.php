<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Models\Part;


class ManufacturerController extends Controller
{
    /**
     * changes part sale status
     * 
     * @return null
     */
    protected function updatePartSaleStatus($id, $status) 
    {
        Part::where('manufacturer_id', $id)->update(["on_sale"=>$status]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manufacturers')->with('manufacturers', Manufacturer::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('manufacturerEditView')->with('state', 'create');
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

        $newManufacturer->name = $request->input('name');
        $newManufacturer->parts_on_sale = 0;
        $newManufacturer->sell_parts = $request->input('sell_parts');

        $issaved = $newManufacturer->save();

        return $issaved ? "success" : "failure";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return View('manufacturerEditView')->
            with('manufacturer', Manufacturer::where('id', $id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return View('manufacturerEditView')->with('manufacturer', Manufacturer::where('id', $id)->first())
            ->with('state', 'edit');
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

        /*this was highly problematic, took 2 hours to find this workaround for, 
          and could not be solved using the attribute method*/
        $sellParts = $request->input('sell_parts');
        if($sellParts) { 
            $old = $existingManufacturer->sell_parts;
            $value = 0;
            if($existingManufacturer->sell_parts == 0) {
                $value = 1;
            }
            $existingManufacturer->sell_parts = $value;
            $existingManufacturer->save();
            $this->updatePartSaleStatus($id, $value);
        }
        

        $issaved = $existingManufacturer->save();

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
        return $isdestroyed ? "success: manufacturer deleted" : "failure: manufacturer not deleted";
    }
}
