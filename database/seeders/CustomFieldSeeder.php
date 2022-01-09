<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
            for ($i = 0; $i <= 5; $i++) {
                $s = DB::table('custom_fields')->insertGetId([
                    'is_active' => $faker->boolean,
                    'image' => $faker->boolean,

                ]);
                DB::table('custom__fields__translations')->insert([[
                    'custom_field_id' => $s,
                    'description' => $faker->sentence(10),
                    'local' => 'en',
                    'name' => $faker->sentence(2),


                ],
                    [
                        'custom_field_id' => $s,
                        'description' => $faker->sentence(10),
                        'local' => 'ar',
                        'name' => $faker->sentence(2),
                    ]]);

            }
        }
}
