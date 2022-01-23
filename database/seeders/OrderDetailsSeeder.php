<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($store_id = 1; $store_id <= 5; $store_id++) {
            for ($order_id = 1; $order_id <= 5; $order_id++) {
                DB::table('order_details')->insert([
                    'store_id' => $store_id,
                    'order_id' => $order_id,
                    'product_id' => $faker->numberBetween(1, 5),
                    'price' => $faker->numberBetween(1000, 5000),
                    'qty' => $faker->numberBetween(100, 500),
                ]);
            }
        }
    }
}
