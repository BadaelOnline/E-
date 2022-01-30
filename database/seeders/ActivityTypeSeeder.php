<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
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
            $s = DB::table('activities_type')->insertGetId([
                'is_active'=>$faker->boolean,
                'activity_id'=>$faker->numberBetween(1,5),
            ]);
            DB::table('activity_type_translations')->insert([[
                'activity_type_id' => $s,
                'local' => 'en',
                'name' => $faker->sentence(5)
            ],
                [
                    'activity_type_id' => $s,
                    'local' => 'ar',
                    'name' => $faker->sentence(5)
                ]]);
        }
    }
}
