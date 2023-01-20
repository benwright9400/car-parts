<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manufacturer;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //due to the specific nature of the data a library such as faker will not be used
        //due to the small numbers of manufacturers required the manufacturers will be added individually

        $manufacturer1 = new Manufacturer;
        $manufacturer1->name = "mercedes";
        $manufacturer1->sell_parts = true;
        $manufacturer1->parts_on_sale = 0;
        $manufacturer1->save();

        $manufacturer1 = new Manufacturer;
        $manufacturer1->name = "bentley";
        $manufacturer1->sell_parts = true;
        $manufacturer1->parts_on_sale = 0;
        $manufacturer1->save();

        $manufacturer1 = new Manufacturer;
        $manufacturer1->name = "volvo";
        $manufacturer1->sell_parts = false;
        $manufacturer1->parts_on_sale = 0;
        $manufacturer1->save();

    }
}
