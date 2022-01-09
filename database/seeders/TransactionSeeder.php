<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
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
            DB::table('transactions')->insert([
                'payment_method_id'=>$faker->numberBetween(1,5),
                'total'=>$faker->numberBetween(1100,2000),

            ]);
        }
    }
}
