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
        for ($i = 1; $i <= 5; $i++) {
            for ($k = 1; $k <= 5; $k++) {
                DB::table('details_custom_values')->insertGetId([
                    'store_products_details_id' => $i,
                    'custom_field_value_id' => $k,
                ]);
            }
        }
    }
}
