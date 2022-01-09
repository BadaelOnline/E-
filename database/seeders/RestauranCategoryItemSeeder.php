<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestauranCategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($item_id = 1; $item_id < 5; $item_id++) {
            for ($restaurant_category_id = 1; $restaurant_category_id < 5; $restaurant_category_id++) {
                DB::table('restaurant_category_item')->insert(
                    [
                        'restaurant_category_id' => $restaurant_category_id,
                        'item_id' => $item_id
                    ]
                );
            }
        }
    }
}
