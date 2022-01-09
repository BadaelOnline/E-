<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
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
            $s = DB::table('restaurants')->insertGetId([
                'image' => $faker->sentence(5),
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
                'social_media_id' => $faker->numberBetween(1,10),
                'appointment_id' =>  $faker->numberBetween(1,10),
                'active_time_id' =>  $faker->numberBetween(1,10),
                'location_id' =>  $faker->numberBetween(1,10),
            ]);

            DB::table('restaurant_translations')->insert([[
                'short_description' =>$faker->sentence(5),
                'long_description' =>$faker->sentence(10),
                'restaurant_id' => $s,
                'title' => $faker->sentence(2),
                'locale' => 'en',
            ],
                [
                    'short_description' =>$faker->sentence(5),
                    'long_description' =>$faker->sentence(10),
                    'restaurant_id' => $s,
                    'title' => $faker->sentence(2),
                    'locale' => 'ar',
                ]]);

        }
    }

}
