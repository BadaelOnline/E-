<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($store_id = 1; $store_id <= 5; $store_id++) {
            for ($payment_method_id = 1; $payment_method_id <= 5; $payment_method_id++) {
                DB::table('store_payment_methods')->insert([
                    'store_id' => $store_id,
                    'payment_method_id' => $payment_method_id,
                ]);
            }
        }
    }
}
