<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($i = 0; $i <= 5; $i++) {
          DB::table('clinics')->insert([
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
                'phone_number' => $faker->phoneNumber,
                'doctor_id' =>$faker->numberBetween(1,10),
                'active_times_id' => $faker->numberBetween(1,10),
                'location_id'=>$faker->numberBetween(1,10),
                'name'  => $faker->sentence(3),

            ]);

        }
    }

}
