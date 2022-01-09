<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class appointmentSedeer extends Seeder
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
            $s = DB::table('appointments')->insertGetId([
                'is_active' => $faker->boolean,
                'is_approved' =>$faker->boolean,
                'morning_evening' =>$faker->boolean,
                'active_times_id' =>$faker->numberBetween(1,10),
                'doctor_id'=>$faker->numberBetween(1,10),
                'customer_id'=>$faker->numberBetween(1,10),
            ]);
            DB::table('appointment_translations')->insert([
                [
                'appointment_id'   => $s,
                'description'  => $faker->sentence(8),
                'locale'      => 'en',
            ],
                [
                    'appointment_id'   => $s,
                    'description'  => $faker->sentence(8),
                    'locale'      => 'ar',
                ]]);

        }
    }
}
