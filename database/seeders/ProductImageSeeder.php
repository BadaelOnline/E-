<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();

        for($product_id=1;$product_id<5;$product_id++){
                DB::table('product_images')->insert(
                    [
                        'product_id'=>$product_id,
                        'image'=>$faker->sentence(10),
                        'is_cover'=>$faker->numberBetween(0, 1)
                    ]
                );
        }
    }
}
