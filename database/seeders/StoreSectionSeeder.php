<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($section_id = 1; $section_id < 6; $section_id++) {
            for ($store_id = 1; $store_id < 6; $store_id++) {
                        DB::table('stores_sections')->insert(
                            [
                                'section_id' => $section_id,
                                'store_id' => $store_id
                            ]
                        );
                    }
                }
            }
}
