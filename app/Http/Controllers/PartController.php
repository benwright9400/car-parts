<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Log;

class PartController extends Controller
{
    /**
     * Check that the manufacturer exists
     * 
     * @return boolean
     */
    protected function manufacturerExists($manufacturerId) {
        $manufacturerCount = Manufacturer::where('id', $manufacturerId)->count();

        return $manufacturerCount > 0 ? true : false;
    }

    /**
     * Check that the SKU is unique when adding or making changes to a row
     * 
     * @return boolean
     */
    public function canUseSKU($SKU, $action = "create") {
        $SKUCount = Part::where('SKU', $SKU)->count();

        if($action === "update") {
            return $SKUCount >= 1 ? true : false;
        } else { // $action === CREATE
            return $SKUCount == 0 ? true : false;
        }
    }
    
    /**
     * Assigns value to object if exists
     * 
     * @returns null
     */
    protected function assignIfExists($column, $request, $partObj) {
        $requestValue = $request->input($column);
        if($requestValue) {
            $partObj->setAttribute($column, $requestValue);
        }
    }

    /**
     * Update manufacturers with sum of all stock
     * 
     * @returns null
     */
    protected function updateManufacturerStock($id) {
        $manufacturer = Manufacturer::where('id', $id)->first();

        $parts = Part::where('manufacturer_id', $id)->get();

        $sum = 0;
        foreach($parts as $part) {
            $sum = $sum + $part['stock_count'];
        }
        $manufacturer->parts_on_sale = $sum;

        $manufacturer->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //produce array with corresponding manufacturer ids as indices
        $formattedManufacturers = [];
        $unformattedManufacturers = Manufacturer::get();

        foreach($unformattedManufacturers as $manufacturer) {
            $formattedManufacturers[$manufacturer['id']] = $manufacturer;
        }

        return view('home')->with('parts', Part::get())->with('manufacturers', $formattedManufacturers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('partsEditView')->with('manufacturers', Manufacturer::get())->with('state', 'create');
    }

    /**
     * returns a view with only parts from a specific manufacturer
     *
     * @return \Illuminate\Http\Response
     */
    public function viewByManufacturer($id)
    {
        $formattedManufacturers = [];
        $unformattedManufacturers = Manufacturer::get();

        foreach($unformattedManufacturers as $manufacturer) {
            $formattedManufacturers[$manufacturer['id']] = $manufacturer;
        }
        
        return View('home')->with('manufacturers', $formattedManufacturers)->
            with('parts', Part::where('manufacturer_id', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newPart = new Part();

        $newPart->name = $request->input('name');
        $newPart->description = $request->input('description');
        $newPart->on_sale = $request->input('on_sale');
        $newPart->stock_count = $request->input('stock_count');

        if($this->canUseSKU($request->input('SKU'))) {
            $newPart->SKU = $request->input('SKU');
        } else {
            return "failure: cannot use SKU";
        }
        
        if($this->manufacturerExists($request->input('manufacturer_id'))) {
            $newPart->manufacturer_id = $request->input('manufacturer_id');
        } else {
            $newPart->manufacturer_id = 1;
        }
        
        // Log::info('newpart: '.$newPart->attributesToArray());
        $issaved = $newPart->save();

        $this->updateManufacturerStock($newPart->manufacturer_id);

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
        return View('partsEditView')->with('manufacturers', Manufacturer::get())->
            with('part', Part::where('id', $id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return View('partsEditView')->with('manufacturers', Manufacturer::get())->
            with('part', Part::where('id', $id)->first())->with('state', 'edit');
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
        $existingPart = Part::where('id', $id)->first();

        if(!$existingPart) {
            return "failure: part does not exist";
        }

        $this->assignIfExists('name', $request, $existingPart);
        $this->assignIfExists('description', $request, $existingPart);
        $this->assignIfExists('stock_count', $request, $existingPart);

        $SKU = $request->input('SKU');
        if($SKU && ($existingPart['SKU'] === $SKU || $this->canUseSKU($SKU))) {
            $existingPart->setAttribute('SKU', $SKU);
        }

        $manufacturerId = $request->input('manufacturer_id');
        if($manufacturerId && $this->manufacturerExists($manufacturerId)) {
            $existingPart->setAttribute('manufacturer_id', $manufacturerId);
        }

        /*this was highly problematic, took 2 hours to find this workaround for, 
          and could not be solved using the attribute method*/
        $sellParts = $request->input('on_sale');
        if($sellParts) { 
            $value = 0;
            if($existingPart->on_sale == 0) {
                $value = 1;
            }
            $existingPart->on_sale = $value;
            $existingPart->save();
        }


        $issaved = $existingPart->save();

        $this->updateManufacturerStock($existingPart->manufacturer_id);
    
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
        $existingPart = Part::where('id', $id)->first();
        $isdestroyed = $existingPart->delete();
        $this->updateManufacturerStock($newPart->manufacturer_id);
        return $isdestroyed ? "success" : "failure";
    }
}
