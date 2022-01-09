<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class RestaurantProductSeeder extends Seeder
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
            $s = DB::table('restaurant_products')->insertGetId([
                'image' => $faker->sentence(5),
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
                'item_id'=>$faker->numberbetween(1,5)
            ]);

            DB::table('restaurant_product_translations')->insert([[
                'short_description' =>$faker->sentence(5),
                'long_description' =>$faker->sentence(10),
                'restaurant_product_id' => $s,
                'name' => $faker->sentence(2),
                'locale' => 'en',
            ],
                [
                    'short_description' =>$faker->sentence(5),
                    'long_description' =>$faker->sentence(10),
                    'restaurant_product_id' => $s,
                    'name' => $faker->sentence(2),

                    'locale' => 'ar',
                ]]);

        }

    }

}
