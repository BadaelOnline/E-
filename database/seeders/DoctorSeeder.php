<?php

namespace Database\Seeders;

use App\Models\Doctors\doctor;
//use Database\Factories\DoctorFactory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DoctorSeeder extends Seeder
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
                $s = DB::table('doctors')->insertGetId([
                    'is_approved' => $faker->boolean,
                    'is_active' => $faker->boolean,
                    'clinic_id' => $faker->numberBetween(1, 10),
                    'user_id' => $faker->numberBetween(1, 10),
                ]);
                DB::table('doctor_translation')->insert([[
                    'description' => $faker->sentence(10),
                    'doctor_id' => $s,
                    'locale' => 'en',
                ],
                    [
                        'description' => $faker->sentence(10),
                        'doctor_id' => $s,
                        'locale' => 'ar',
                    ]]);

            }
        }
}
