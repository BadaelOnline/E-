<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalMedicalDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($hospital_id = 1; $hospital_id < 5; $hospital_id++) {
            for ($medical_device_id = 1; $medical_device_id < 5; $medical_device_id++) {
                DB::table('hospital_medical_device')->insert(
                    [
                        'hospital_id' => $hospital_id,
                        'medical_device_id' => $medical_device_id
                    ]
                );
            }
        }
    }
}
