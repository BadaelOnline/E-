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
                'loc_id' =>  $faker->numberBetween(1,10),
                'country_id' =>  $faker->numberBetween(1,10),
                'gov_id' =>  $faker->numberBetween(1,10),
                'city_id'=>  $faker->numberBetween(1,10),
                'offer_id'=> $faker->numberBetween(1,10),
                'street_id'=>  $faker->numberBetween(1,10),
                'followers_id'=> $faker->numberBetween(1,10),
                'delivery'=> $faker->boolean,
                'socialMedia_id'=> $faker->numberBetween(1,10),
                'edalilyPoint'=>$faker->sentence(1),
                'rating'=>$faker->sentence(1),
                'workingHours'=>$faker->sentence(1),
                'logo'=>$faker->sentence(1),
            ]);
            DB::table('store_translations')->insert([
                [
                'title' => $faker->sentence(5),
                'local' => 'en',
                'store_id' => $s
            ],
                [
                    'title' => $faker->sentence(5),
                    'local' => 'ar',
                    'store_id' => $s
                ]
            ]);

        }
    }
}
