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
        $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            DB::table('plans')->insert([
                'name'=>$faker->word(),
                'price_per_month'=>$faker->numberBetween(1000,8000),
            ]);
        }
    }
}
