<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailsCustomValue extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($j = 1; $j <= 5; $j++) {
            for ($i = 1; $i <= 5; $i++) {
                $s = DB::table('details_custom_values')->insert([
                    'store_products_details_id' => $j,
                    'custom_field_value_id' => $i,
                ]);
            }
        }
    }
}
