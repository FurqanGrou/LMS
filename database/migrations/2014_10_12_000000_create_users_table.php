<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_number');
            $table->string('name');
            $table->string('father_phone');
            $table->string('mother_phone');
            $table->string('father_mail');
            $table->string('mother_mail');
            $table->string('language');
            $table->string('status');
            $table->string('section');
            $table->string('login_time');
            $table->string('path');
            $table->string('password');
            $table->string('class_number');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
