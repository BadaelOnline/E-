<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReachSeeder extends Seeder
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
            DB::table('reaches')->insert([
                'type' => $faker->word(),
                'location_id' => $faker->numberBetween(1, 5),
                'social_media_id' => $faker->numberBetween(1, 5),
                'type_id' => $faker->numberBetween(1, 5),
            ]);
        }
    }
}
