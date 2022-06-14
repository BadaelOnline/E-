<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
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
            $s = DB::table('stores')->insertGetId([
                'currency_id' =>  $faker->numberBetween(1,10),
                'location_id' => 1,
                'social_media_id' =>  1,
                'activity_type_id'=>  1,
                'owner_id'=> 1,
                'section_id'=> 1,
                'is_active'=> $faker->boolean(),
                'is_approved'=> $faker->boolean(),
                'logo'=>$faker->imageUrl(),
            ]);
            DB::table('store_translations')->insert([
                [
                'name' => $faker->sentence(5),
                'local' => 'en',
                'store_id' => $s
            ],
                [
                    'name' => $faker->sentence(5),
                    'local' => 'ar',
                    'store_id' => $s
                ]
            ]);

        }
    }
}
