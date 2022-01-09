<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            $s = DB::table('brands')->insertGetId([
                'is_active'=>$faker->boolean,
                'slug'=>$faker->sentence(3),
                'image'=>$faker->sentence(3)
            ]);
            DB::table('brand_translation')->insert([[
                'brand_id' => $s,
                'description' => $faker->sentence(10),
                'local' => 'en',
                'name' => $faker->sentence(5)
            ],
                [
                    'brand_id' => $s,
                    'description' => $faker->sentence(10),
                    'local' => 'ar',
                    'name' => $faker->sentence(5)
                ]]);
        }
    }
}
