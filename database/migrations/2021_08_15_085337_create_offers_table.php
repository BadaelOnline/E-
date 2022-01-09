<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->unsigned();
            $table->integer('store_product_id')->unique();
            $table->string('user_email');
            $table->string('image');
            $table->integer('position');
            $table->integer('price');
            $table->integer('selling_price');
            $table->integer('quantity');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->boolean('is_active');
            $table->boolean('is_offer');
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
        Schema::dropIfExists('offers');
    }
}
