<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorMedicalDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($doctor_id=1;$doctor_id<5;$doctor_id++) {
            for ($medical_device_id = 1; $medical_device_id < 5; $medical_device_id++) {
                DB::table('doctor_medical_device')->insert(
                    [
                        'doctor_id' => $doctor_id,
                        'medical_device_id' => $medical_device_id
                    ]
                );
            }
        }
    }
}
