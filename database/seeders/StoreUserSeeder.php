<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreUserSeeder extends Seeder
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
            DB::table('store_users')->insert([
                'store_id' => $faker->numberBetween(1, 5),
                'user_id' => $faker->numberBetween(6, 10),
            ]);
            }
    }
}
