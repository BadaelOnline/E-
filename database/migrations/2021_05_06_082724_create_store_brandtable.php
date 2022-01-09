<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStoreBrandtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('store_brand', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('brand_id');
            $table->timestamps();
        });

    }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
    {
        Schema::dropIfExists('_store_brand');
    }
}
