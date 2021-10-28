<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // student
            $table->integer('chapter_id'); // chapter
            $table->date('start_date');
            $table->date('end_date');
            $table->string('teacher_name'); // do exam
            $table->integer('teacher_id'); // submitted_by
            $table->integer('mistakes_number')->nullable();
            $table->integer('alerts_number')->nullable();
            $table->integer('tajweed_mistakes_number')->nullable();
            $table->integer('memo_result')->default(0);
            $table->integer('tajweed_result')->default(0);
            $table->date('exam_date')->nullable();
            $table->string('teacher_notes')->nullable();
            $table->string('tester_name')->nullable();
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
        Schema::dropIfExists('exam_requests');
    }
}
