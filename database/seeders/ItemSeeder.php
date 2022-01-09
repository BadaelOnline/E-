<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
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
            $s = DB::table('items')->insertGetId([
                'image' => $faker->sentence(5),
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
                'restaurant_product_id' =>$faker->numberBetween(1,10),
            ]);

            DB::table('item_translations')->insert([[
                'short_description' =>$faker->sentence(5),
                'long_description' =>$faker->sentence(10),
                'item_id' => $s,
                'name' => $faker->sentence(2),
                'locale' => 'en',
            ],
                [
                    'short_description' =>$faker->sentence(5),
                    'long_description' =>$faker->sentence(10),
                    'item_id' => $s,
                    'name' => $faker->sentence(2),

                    'locale' => 'ar',
                ]]);

        }

    }
}
