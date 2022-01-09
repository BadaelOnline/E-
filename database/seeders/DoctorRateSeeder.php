<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorRateSeeder extends Seeder
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
            DB::table('doctor_rates')->insert([
                'doctor_id' => $faker->numberBetween(1, 10),
                'rate' => $faker->numberBetween(1, 5),
                'is_active' => $faker->boolean
            ]);

            }
        }
}
