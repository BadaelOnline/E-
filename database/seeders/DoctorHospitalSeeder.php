<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorHospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($doctor_id = 1; $doctor_id < 5; $doctor_id++) {
                for ($hospital_id = 1; $hospital_id < 5; $hospital_id++) {
                    DB::table('doctor_hospital')->insert(
                        [
                            'doctor_id' => $doctor_id,
                            'hospital_id' => $hospital_id
                        ]
                    );
                }
        }
    }
}
