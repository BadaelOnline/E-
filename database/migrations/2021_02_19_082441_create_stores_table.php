<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id')->index();
            $table->unsignedBigInteger('reaches_id')->index();
            $table->unsignedBigInteger( 'activity_type_id')->index();
            $table->unsignedBigInteger( 'owner_id')->index();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->string( 'logo');
            $table->timestamps();
        });
    }
    /**
     * Rever
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
