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
        for ($store_id = 1; $store_id <= 5; $store_id++) {
            for ($user_id = 1; $user_id <= 5; $user_id++) {
                DB::table('store_users')->insert([
                    'store_id' => $store_id,
                    'user_id' => $user_id,
                ]);
            }
        }
    }
}
