<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($product_id=1;$product_id<5;$product_id++){
            for($customfield_id=1;$customfield_id<3;$customfield_id++) {
                DB::table('products_custom_fields')->insert(
                    [
                        'customfield_id'=>$customfield_id,
                        'product_id'=>$product_id
                    ]);
            }
        }

    }
}
