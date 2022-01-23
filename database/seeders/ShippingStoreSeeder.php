<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //store_shipping
         $faker=Faker::create();
        for ($store_id = 1; $store_id <= 5; $store_id++) {
            for ($shipping_id = 1; $shipping_id <= 5; $shipping_id++) {
                DB::table('store_shipping')->insert([
                    'store_id' => $store_id,
                    'shipping_id' => $shipping_id,
                ]);
            }
        }
    }
}
