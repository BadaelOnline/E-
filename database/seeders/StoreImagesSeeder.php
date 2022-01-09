<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for($store_id=1;$store_id<5;$store_id++){
            for($image=1;$image<3;$image++) {
                DB::table('store_images')->insert(
                    [
                        'store_id'=>$store_id,
                        'image'=>$faker->sentence(1),
                        'is_cover'=>0,
                    ]
                );
            }
        }
    }
}
