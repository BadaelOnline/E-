<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttachmentSeeder extends Seeder
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
            DB::table('attachments')->insert([
                'path' => $faker->word(),
                'attachments_type_id' => $faker->numberBetween(1, 5),
                'record_num' => $faker->numberBetween(1000, 5000),
            ]);
        }
    }
}
