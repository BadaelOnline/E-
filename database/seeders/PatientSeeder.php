<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
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
            DB::table('patients')->insert([
                'is_active' => $faker->boolean,
                'is_approved' => $faker->boolean,
                'note'=>$faker->sentence(10),
                'blood_type' => $faker->bloodType(),
                'social_status' => $faker->sentence(1),
                'gender' =>$faker->sentence(1),
                'medical_file_number' => $faker->numberBetween(1,10),
                'medical_file_date' => $faker->date('2021-05-19'),
                'review_date' => $faker->date('2021-05-25'),
                'PDF' => $faker->sentence(5),
            ]);
        }
    }
}
