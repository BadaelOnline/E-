<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActiveTimeSedeer extends Seeder
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
            DB::table('active_times')->insert([
                'is_active' => $faker->boolean,
                'is_approved' => $faker->boolean,
                'start_time'=>$faker->time('21:12:31'),
                'end_time' => $faker->time('21:12:31')
            ]);
        }
    }
}
