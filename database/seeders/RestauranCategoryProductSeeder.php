<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestauranCategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($restaurant_product_id = 1; $restaurant_product_id < 5; $restaurant_product_id++) {
            for ($restaurant_category_id = 1; $restaurant_category_id < 5; $restaurant_category_id++) {
                DB::table('restaurant_category_restaurant_product')->insert(
                    [
                        'restaurant_category_id' => $restaurant_category_id,
                        'restaurant_product_id' => $restaurant_product_id
                    ]
                );
            }
        }
    }
}
