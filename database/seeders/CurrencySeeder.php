<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
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
             DB::table('currencies')->insert([
                'currency'=>$faker->name(),
                'symbol'=>$faker->word(),
                'code'=>$faker->currencyCode()
            ]);
        }
    }
}
