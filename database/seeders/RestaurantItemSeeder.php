<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =Faker::create();
        for ($i=1;$i<=5;$i++){
            DB::table('restaurant_item')->insert([
                'is_active' => $faker->boolean,
                'is_approved' => $faker->boolean,
                'restaurant_id'=>$faker->numberBetween(1,6),
                'item_id'=>$faker->numberBetween(1,6),
                'price' => $faker->numberBetween(1000,10000),
                'quantity' => $faker->numberBetween(1,10),

            ]);

        }
    }
}
