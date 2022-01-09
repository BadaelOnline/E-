<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('phone_number');
            $table->string('whatsapp_number');
            $table->string('facebook_account');
            $table->string( 'instagram_account');
            $table->string('telegram_number');
            $table->string( 'email');
            $table->boolean('is_active');
            $table->unsignedInteger('reach_id');
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
        Schema::dropIfExists('social_media');
    }
}
