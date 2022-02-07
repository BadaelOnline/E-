<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            $s = DB::table('plans')->insertGetId([
                'is_active' => $faker->boolean,
                'activity_id' => $faker->numberBetween(1, 5),
                'price' => $faker->numberBetween(10000, 50000),
                'num_of_month' => $faker->numberBetween(1, 12),
                'discount' => $faker->numberBetween(1, 100),
            ]);
            DB::table('plan_translations')->insert([[
                'plan_id' => $s,
                'local' => 'en',
                'name' => $faker->sentence(5)
            ],
                [
                    'plan_id' => $s,
                    'local' => 'ar',
                    'name' => $faker->sentence(5)
                ]
            ]);
        }
    }
}
