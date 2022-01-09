<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStoresSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('store_id');
            $table->timestamps();
        });
        for($store_id=1;$store_id<12;$store_id++){
            for($section_id=1;$section_id<12;$section_id++) {
                DB::table('stores_sections')->insert(
                    $arr = [
                        'section_id'=>$section_id,
                        'store_id'=>$store_id
                    ]
                );
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('stores_sections');
    }
}
