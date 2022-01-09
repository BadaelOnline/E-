<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
            for ($i = 1; $i <= 5; $i++) {
                $s = DB::table('products')->insertGetId([
                    'slug' => $faker->sentence(1),
                    'barcode' => $faker->sentence(1),
                    'is_active' => $faker->boolean,
                    'is_appear' => $faker->boolean,
                    'brand_id' => $faker->numberBetween(1, 2)
                ]);
                DB::table('product_translations')->insert([[
                    'name' => $faker->sentence(5),
                    'short_des' => $faker->sentence(10),
                    'local' => 'en',
                    'long_des' => $faker->sentence(10),
                    'meta' => $faker->sentence(5),
                    'product_id' => $s
                ],
                    [
                        'name' => $faker->sentence(5),
                        'short_des' => $faker->sentence(10),
                        'local' => 'ar',
                        'long_des' => $faker->sentence(10),
                        'meta' => $faker->sentence(5),
                        'product_id' => $s
                    ]]);

            }
        }
}
