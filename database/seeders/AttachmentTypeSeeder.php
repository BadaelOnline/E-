<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttachmentTypeSeeder extends Seeder
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
            $s = DB::table('attachments_type')->insertGetId([
                'is_active'=>$faker->boolean,
            ]);
            DB::table('attachments_type_translation')->insert([[
                'attachment_type_id' => $s,
                'local' => 'en',
                'name' => $faker->word(5)
            ],
                [
                    'activity_type_id' => $s,
                    'local' => 'ar',
                    'name' => $faker->word(5)
                ]]);
        }
    }
}
