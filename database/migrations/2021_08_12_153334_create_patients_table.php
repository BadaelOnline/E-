<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string( 'gender');
            $table->string( 'social_status');
            $table->string( 'blood_type');
            $table->string( 'note');
            $table->integer('medical_file_number');
            $table->date('medical_file_date');
            $table->date('review_date');
            $table->string('PDF');
            $table->boolean('is_active');
            $table->boolean('is_approved');
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
        Schema::dropIfExists('patients');
    }
}
