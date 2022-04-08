<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
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
            $s = DB::table('offers')->insertGetId([
                'is_offer' => $faker->boolean,
                'is_active' => $faker->boolean,
                'user_email' => $faker->email,
                'offer_price' => $faker->numberBetween(1000, 6000),
                'selling_quantity' => $faker->numberBetween(1, 10),
                'started_at'=>$faker->dateTimeBetween('+1 week', '+1 month'),
                'ended_at' => $faker->dateTimeBetween('+1 week', '+1 month')

            ]);
            DB::table('offer_translations')->insert([[
                'name'=>$faker->sentence(2),
                'short_desc' => $faker->sentence(5),
                'long_desc' => $faker->sentence(10),
                'offer_id' => $s,
                'locale' => 'en',
            ],
                ['name'=>$faker->sentence(2),
                    'short_desc' => $faker->sentence(5),
                    'long_desc' => $faker->sentence(10),
                    'offer_id' => $s,
                    'locale' => 'ar',
                ]]);

        }
    }
}
