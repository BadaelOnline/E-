<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
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
            DB::table('banners')->insert([
                'store_id' => $faker->numberBetween(1, 5),
                'is_active' => $faker->boolean(),
                'image' => $faker->image(),
                'description' => $faker->sentence(5),
                'is_appear' => $faker->boolean(),
            ]);
        }
    }
}
