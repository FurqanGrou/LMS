<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes_teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('class_number');
            $table->unsignedBigInteger('teacher_number');

            $table->timestamps();

            $table->foreign('class_number')->references('class_number')->on('classes');
            $table->foreign('teacher_number')->references('teacher_number')->on('teachers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classess_teachers');
    }
}
