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
        for ($payment_method_id = 1; $payment_method_id <= 5; $payment_method_id++) {
            for ($total = 1; $total <= 5; $total++) {
                DB::table('transactions')->insert([
                    'payment_method_id' => $payment_method_id,
                    'total' => $total,

                ]);
            }
        }
    }
}
