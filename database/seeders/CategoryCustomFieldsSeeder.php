<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryCustomFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($category_id=1;$category_id<5;$category_id++){
            for($custom_field_id=1;$custom_field_id<5;$custom_field_id++) {
                DB::table('category_customfields')->insert(
                    [
                        'custom_field_id'=>$custom_field_id,
                        'category_id'=>$category_id
                    ]);
            }
        }

    }
}
