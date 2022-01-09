<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($store_id = 1; $store_id < 6; $store_id++) {
            for ($brand_id = 1; $brand_id < 6; $brand_id++) {
                DB::table('store_brand')->insert(
                    [
                        'brand_id' => $brand_id,
                        'store_id' => $store_id
                    ]
                );
            }
        }
    }
}
