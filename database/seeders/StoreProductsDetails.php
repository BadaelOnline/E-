<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreProductsDetails extends Seeder
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
                $s = DB::table('store_product_details')->insert([
                    'price' => $faker->numberBetween(100, 5000),
                    'quantity' => $faker->numberBetween(0, 100),
                    'store_products_id' => $j
                ]);
            }
        }
    }
}
