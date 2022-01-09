<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialMediaSedeer extends Seeder
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
            DB::table('social_media')->insert([
                'is_active' => $faker->boolean,
                'phone_number' => $faker->phoneNumber,
                'whatsapp_number' => $faker->phoneNumber,
                'facebook_account' => $faker->url,
                'instagram_account' => $faker->url,
                'telegram_number' => $faker->phoneNumber,
                'email' => $faker->email,
                'user_id' => $faker->unique()->numberBetween(1,10)

            ]);
        }
    }
}
