<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomField_CustomFieldValue extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for($customFiled=1;$customFiled<5;$customFiled++){
            for($value=1;$value<5;$value++) {
                DB::table('custom_field_value')->insert(
                    [
                        'custom_field_id'=>$customFiled,
                        'value'=>$faker->sentence(1)
                    ]
                );
            }
        }
    }
}
