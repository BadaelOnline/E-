<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
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
            $s = DB::table('categories')->insertGetId([
                'slug' => $faker->sentence(1),
                'is_active' => $faker->boolean,
                'image' => $faker->boolean,
                'parent_id' => $faker->numberBetween(1,5),
                'section_id' =>  $faker->numberBetween(1,10)
            ]);
            DB::table('category_translations')->insert([[
                'name' => $faker->sentence(5),
                'local' => 'en',
                'category_id' => $s
            ],
                [
                    'name' => $faker->sentence(5),
                    'local' => 'ar',
                    'category_id' => $s
                ]]);

        }
    }
}
