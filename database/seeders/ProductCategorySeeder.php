<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($category_id = 1; $category_id < 5; $category_id++) {
            for ($product_id = 1; $product_id < 5; $product_id++) {
                        DB::table('products_categories')->insert(
                            [
                                'category_id' => $category_id,
                                'product_id' => $product_id
                            ]);
                    }
                }
            }
}
