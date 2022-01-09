<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
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
            DB::table('subscriptions')->insert([
                'store_id'=>$faker->numberBetween(1,5),
                'plan_id'=>$faker->numberBetween(1,5),
                'start_date'=>$faker->date('Y-m-d'),
                'end_date'=>$faker->date('Y-m-d'),
                'transaction_id'=>$faker->numberBetween(1,5),
            ]);
        }
    }
}
