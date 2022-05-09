<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCustomValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($product_id=1;$product_id<5;$product_id++){
            for($custom_field_value_id=1;$custom_field_value_id<3;$custom_field_value_id++) {
                DB::table('products_custom_field_value')->insert(
                    [
                        'custom_field_value_id'=>$custom_field_value_id,
                        'product_id'=>$product_id
                    ]);
            }
        }

    }
}
