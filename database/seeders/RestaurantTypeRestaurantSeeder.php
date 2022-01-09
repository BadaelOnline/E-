<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantTypeRestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($restaurant_id = 1; $restaurant_id < 5; $restaurant_id++) {
            for ($restaurant_type_id = 1; $restaurant_type_id < 5; $restaurant_type_id++) {
                DB::table('restaurant_restaurant_type')->insert(
                    [
                        'restaurant_type_id' => $restaurant_type_id,
                        'restaurant_id' => $restaurant_id
                    ]
                );
            }
        }
    }
}
