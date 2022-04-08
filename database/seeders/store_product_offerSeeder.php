<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class store_product_offerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($offer_id = 1; $offer_id < 6; $offer_id++) {
            for ($store_product_id = 1; $store_product_id < 2; $store_product_id++) {
                        DB::table('store_products_offers')->insert(
                            [
                                'offer_id' => $offer_id,
                                'store_product_id' => $store_product_id
                            ]
                        );
                    }
                }
            }
}
