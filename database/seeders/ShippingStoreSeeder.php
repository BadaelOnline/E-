<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //store_shipping
         $faker=Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            DB::table('store_shipping')->insert([
                'store_id'=>$faker->numberBetween(1,5),
                'shipping_id'=>$faker->numberBetween(6,10),
            ]);
        }

    }
}
