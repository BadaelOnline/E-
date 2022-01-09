<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($section_id = 1; $section_id < 5; $section_id++) {
            for ($product_id = 1; $product_id < 5; $product_id++) {
                        DB::table('products_sections')->insert(
                            [
                                'section_id' => $section_id,
                                'product_id' => $product_id
                            ]
                        );
                    }
                }
        for ($section_id = 1; $section_id < 6; $section_id=$section_id+2) {
            for ($product_id = 1; $product_id < 5; $product_id++) {
                DB::table('products_sections')->insert(
                    [
                        'section_id' => $section_id,
                        'product_id' => $product_id
                    ]
                );
            }
        }
            }
}
