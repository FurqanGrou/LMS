<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('new_lesson')->nullable();
            $table->string('new_lesson_from')->nullable();
            $table->string('new_lesson_to')->nullable();
            $table->string('last_5_pages')->nullable();
            $table->string('daily_revision')->nullable();
            $table->string('daily_revision_from')->nullable();
            $table->string('daily_revision_to')->nullable();
            $table->string('mistake')->nullable();
            $table->string('alert')->nullable();
            $table->string('number_pages')->nullable();
            $table->string('listener_name')->nullable();

            $table->string('lesson_grade')->nullable();
            $table->string('last_5_pages_grade')->nullable();
            $table->string('daily_revision_grade')->nullable();
            $table->string('behavior_grade')->nullable();
            $table->string('total')->nullable();
            $table->string('notes_to_parent')->nullable();

            $table->string('student_id')->nullable();
            $table->string('class_id')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
