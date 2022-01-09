<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reaches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('social_media_id');
            $table->string('type');
            $table->unsignedInteger('type_id');
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
        Schema::dropIfExists('reaches');
    }
}
