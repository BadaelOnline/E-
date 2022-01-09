<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantManagerSeeder extends Seeder
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
            DB::table('restaurant_managers')->insert([
                'is_active' => $faker->boolean,
                'is_approved' => $faker->boolean,
                'user_id'=>$faker->numberBetween(1,10)
            ]);
        }
    }
}
