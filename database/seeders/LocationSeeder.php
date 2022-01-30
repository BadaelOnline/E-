<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            DB::table('locations')->insert([
                'name'=>$faker->address(),
                'address'=>$faker->address(),
                'latitude'=>$faker->latitude(),
                'longitude'=>$faker->longitude(),
                'is_active'=>$faker->boolean(),
            ]);
        }
    }
}
