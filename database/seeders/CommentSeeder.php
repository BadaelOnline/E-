<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\factory as Faker;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
            foreach (range(1,5 )as $value){
                DB::table('comments')->insert([
                    'user_id' =>$faker->numberBetween(1,10),
                    'offer_id'=>$faker->numberBetween(1,10),
                    'comment'=>$faker->sentence(15),
                    'is_active'=>$faker->boolean,
                    'is_approved'=>$faker->boolean,
                ]);
            }

    }
}
