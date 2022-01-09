<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($i = 0; $i <= 5; $i++) {
            $s = DB::table('restaurant_categories')->insertGetId([
                'image' => $faker->sentence(5),
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
            ]);

            DB::table('restaurant_category_translations')->insert([[
                'short_description' =>$faker->sentence(5),
                'long_description' =>$faker->sentence(10),
                'restaurant_category_id' => $s,
                'name' => $faker->sentence(2),
                'locale' => 'en',
            ],
                [
                    'short_description' =>$faker->sentence(5),
                    'long_description' =>$faker->sentence(10),
                    'restaurant_category_id' => $s,
                    'name' => $faker->sentence(2),

                    'locale' => 'ar',
                ]]);

        }

    }
}
