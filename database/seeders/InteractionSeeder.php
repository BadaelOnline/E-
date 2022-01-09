<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        foreach (range(1,5)as $value){
            DB::table('interactions')->insert([
                'user_id'=>$faker->numberBetween(1,10),
                'offer_id'=>$faker->numberBetween(1,10),
                'interaction_type'=>$faker->numberBetween(1,5),
                'is_active'=>$faker->boolean,
            ]);
        }
    }
}
