<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($sections_id = 1; $sections_id < 5; $sections_id++) {
            for ($brands_id = 1; $brands_id < 5; $brands_id++) {
                        DB::table('brand_sections')->insert(
                            [
                                'brands_id' => $brands_id,
                                'sections_id' => $sections_id
                            ]);

                    }
                }
            }
}
