<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($location_id = 1; $location_id <= 5; $location_id++) {
            for ($user_id = 1; $user_id <= 5; $user_id++) {
                DB::table('user_locations')->insert([
                    'location_id' => $location_id,
                    'user_id' => $user_id,
                ]);
            }
        }
    }
}
