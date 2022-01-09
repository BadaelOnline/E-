<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorPatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($doctor_id = 1; $doctor_id < 5; $doctor_id++) {
            for ($patient_id = 1; $patient_id < 5; $patient_id++) {
                DB::table('doctor_patient')->insert(
                    [
                        'doctor_id' => $doctor_id,
                        'patient_id' => $patient_id
                    ]
                );
            }
        }
    }
}
