<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_product_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('restaurant_product_id')->unsigned();
            $table->string('locale');
            $table->string('name');
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
        Schema::dropIfExists('restaurant_product_translations');
    }
}
