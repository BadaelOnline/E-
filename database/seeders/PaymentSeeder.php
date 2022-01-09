<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            DB::table('payment_methods')->insert([
                'name'=>$faker->name(),
            ]);
        }
    }
}
