<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class StoreProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            for ($k = 1; $k <= 5; $k++) {
                DB::table('stores_products')->insertGetId([
                    'store_id' => $i,
                    'product_id' => $k,
                    'price' => $faker->numberBetween(200, 10000),
                    'quantity' => $faker->numberBetween(1, 20),
                    'is_active' => $faker->boolean,
                    'is_appear' => $faker->boolean,
                ]);
            }
        }
    }
}
