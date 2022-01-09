<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->boolean('is_active');
            $table->timestamps();
        });
        Schema::create('role_translation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('local');
            $table->string('role_id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description');
            $table->timestamps();
        });
        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->boolean('is_active');
            $table->timestamps();
        });
        Schema::create('permission_translation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('local');
            $table->string('permission_id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description');
            $table->timestamps();
        });
        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
//            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([
                'user_id'
                , 'role_id'
//                , 'user_type'
            ]);
        });
        // Create table for associating roles to employees and teams (Many To Many Polymorphic)
        Schema::create('role_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([
                'employee_id'
                , 'role_id'
            ]);
        });
        // Create table for associating roles to type_users and teams (Many To Many Polymorphic)
        Schema::create('role_type', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('type_id');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([
                'type_id'
                , 'role_id'
            ]);
        });
        // Create table for associating permissions to users (Many To Many Polymorphic)
//        Schema::create('permission_user', function (Blueprint $table) {
//            $table->unsignedBigInteger('permission_id');
//            $table->unsignedBigInteger('user_id');
//            $table->foreign('permission_id')->references('id')->on('permissions')
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->primary([
//                'user_id',
//                'permission_id',
//            ]);
//        });
        // Create table for associating permissions to users (Many To Many Polymorphic)
//        Schema::create('permission_employee', function (Blueprint $table) {
//            $table->unsignedBigInteger('permission_id');
//            $table->unsignedBigInteger('employee_id');
//            $table->foreign('permission_id')->references('id')->on('permissions')
//                ->onUpdate('cascade')->onDelete('cascade');
//
//            $table->primary([
//                'employee_id',
//                'permission_id',
//            ]);
//        });
        // Create table for associating permissions to users (Many To Many Polymorphic)
//        Schema::create('permission_type', function (Blueprint $table) {
//            $table->unsignedBigInteger('permission_id');
//            $table->unsignedBigInteger('type_id');
//            $table->foreign('permission_id')
//                ->references('id')->on('permissions')
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->primary([
//                'type_id',
//                'permission_id',
//            ]);
//        });
        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
//        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
//        Schema::dropIfExists('permission_employee');
//        Schema::dropIfExists('permission_type');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('role_employee');
        Schema::dropIfExists('role_type');
        Schema::dropIfExists('roles');
    }
}
