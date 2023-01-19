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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //page display handled within the react app
        return view('home')->with('parts', Part::get());
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
        $newPart = new Part();

        $newPart->name = $request->input('name');
        if($this->canUseSKU($request->input('SKU'))) {
            $newPart->SKU = $request->input('SKU');
        } else {
            return "failure: cannot use SKU";
        }
        $newPart->description = $request->input('description');
        $newPart->on_sale = $request->input('on_sale');
        if($this->manufacturerExists($request->input('manufacturer_id'))) {
            $newPart->manufacturer_id = $request->input('manufacturer_id');
        } else {
            $newPart->manufacturer_id = 1;
        }
        $newPart->stock_count = $request->input('stock_count');
        // Log::info('newpart: '.$newPart->attributesToArray());
        $issaved = $newPart->save();

        return $issaved ? "success" : "failure";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) //if no id return all
    {
        // Part::where('id', $id)->first();
        return View('manufacturerEditView')->
        with('manufacturers', Manufacturer::get())->
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
        //page display handled within the react app
        return View('manufacturerEditView')->
        with('manufacturers', Manufacturer::get())->
        with('part', Part::where('id', $id)->first())->
        with('state', 'edit');
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
        if($request->input('special_command') == "manufacturer_sellable") {
            $isUpdated = Part::where('manufacturer_id', $request->input('manufacturer_id'))
            ->update(['on_sale' => $request->input('on_sale')]);
            return $isUpdated ? "success" : "failure";
        }

        $existingPart = Part::where('id', $id)->first();
        if(!$existingPart) {
            return "failure";
        }
        $this->assignIfExists('name', $request, $existingPart);
        $this->assignIfExists('description', $request, $existingPart);
        $this->assignIfExists('on_sale', $request, $existingPart);
        $this->assignIfExists('stock_count', $request, $existingPart);

        //special cases
        $SKU = $request->input('SKU');
        if($SKU && ($existingPart['SKU'] === $SKU || $this->canUseSKU($SKU))) {
            $existingPart->setAttribute('SKU', $SKU);
        }

        $manufacturerId = $request->input('manufacturer_id');
        if($manufacturerId && $this->manufacturerExists($manufacturerId)) {
            $existingPart->setAttribute('manufacturer_id', $manufacturerId);
        }

        $issaved = $existingPart->save();
    
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
        return $isdestroyed ? "success" : "failure";
    }
}
