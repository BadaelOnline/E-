<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($doctor_id=1;$doctor_id<5;$doctor_id++) {
            for ($specialty_id = 1; $specialty_id < 3; $specialty_id++) {
                DB::table('doctor_specialty')->insert(
                    [
                        'doctor_id' => $doctor_id,
                        'specialty_id' => $specialty_id
                    ]
                );
            }
        }
    }
}
