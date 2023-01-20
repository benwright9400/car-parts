<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Part;
use App\Models\Manufacturer;

class PartSeeder extends Seeder
{

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
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $example_SKU = 111111;
        for($i = 0; $i < 10; $i++) {
            $newPart = new Part;
            $newPart->name = "wheel " . $i;
            $newPart->SKU = "".$example_SKU;
            $newPart->description = "it rolls " . $i . " times.";
            $newPart->stock_count = rand(10,20);
            $newPart->manufacturer_id = rand(1,3);

            $manufacturer = Manufacturer::where('id', $newPart->manufacturer_id)->first();

            $newPart->on_sale = $manufacturer->sell_parts;
            $newPart->save();

            $example_SKU++;
        }

        $manufacturers = Manufacturer::get();
        foreach($manufacturers as $manufacturer) {
            $this->updateManufacturerStock($manufacturer['id']);
        }
    }
}
