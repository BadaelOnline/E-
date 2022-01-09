<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_type_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('restaurant_type_id')->unsigned();
            $table->string('locale');
            $table->string('title');
            $table->string('short_description');
            $table->string('long_description');
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
        Schema::dropIfExists('restaurant_type_translations');
    }
}
