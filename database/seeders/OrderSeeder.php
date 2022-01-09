<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
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
            DB::table('orders')->insert([
                'user_id'=>$faker->numberBetween(1,5),
                'Payment_Method_id'=>$faker->numberBetween(1,5),
                'shipping_id'=>$faker->numberBetween(1,5),
                'total'=>$faker->numberBetween(1000,5000),
                'state'=>$faker->boolean(),
                'is_active'=>$faker->boolean(),
            ]);
        }
    }
}
