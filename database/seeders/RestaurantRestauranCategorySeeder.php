<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantRestauranCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($restaurant_id = 1; $restaurant_id < 5; $restaurant_id++) {
            for ($restaurant_category_id = 1; $restaurant_category_id < 5; $restaurant_category_id++) {
                DB::table('restaurant_restaurant_category')->insert(
                    [
                        'restaurant_category_id' => $restaurant_category_id,
                        'restaurant_id' => $restaurant_id
                    ]
                );
            }
        }
    }
}
