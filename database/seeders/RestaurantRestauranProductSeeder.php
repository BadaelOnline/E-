<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantRestauranProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($restaurant_id = 1; $restaurant_id < 5; $restaurant_id++) {
            for ($restaurant_product_id = 1; $restaurant_product_id < 5; $restaurant_product_id++) {
                DB::table('restaurant_restaurant_product')->insert(
                    [
                        'restaurant_product_id' => $restaurant_product_id,
                        'restaurant_id' => $restaurant_id
                    ]
                );
            }
        }
    }
}
