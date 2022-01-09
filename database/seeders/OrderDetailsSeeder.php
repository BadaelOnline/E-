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
        for ($i = 1; $i <= 5; $i++) {
            DB::table('order_details')->insert([
                'store_id' => $faker->numberBetween(1, 5),
                'order_id' => $faker->numberBetween(1, 5),
                'product_id' => $faker->numberBetween(1, 5),
                'price' => $faker->numberBetween(1000, 5000),
                'qty' => $faker->numberBetween(100, 500),
            ]);
        }
    }
}
