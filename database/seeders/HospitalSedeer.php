<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalSedeer extends Seeder
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
            $s = DB::table('hospitals')->insertGetId([
                'is_active' => $faker->boolean,
                'is_approved' => $faker->boolean,
                'general_hospital' => $faker->boolean,
                'private_hospital' => $faker->boolean,
                'location_id' => $faker->numberBetween(1,6),
                ]);

        }
        DB::table('hospital_translations')->insert([[
            'name' => $faker->sentence(5),
            'description' => $faker->sentence(5),
            'hospital_id' => $s,
              'locale' => 'en',
        ],
            [
                'name' => $faker->sentence(5),
                'description' => $faker->sentence(5),
                'hospital_id' => $s,
                'locale' => 'ar',
            ]]);

    }
}
