<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_pages', function (Blueprint $table) {
            $table->id();
            $table->integer('part_id');
            $table->double('lesson_number');
            $table->string('lesson_title');
            $table->integer('start_page_number');
            $table->integer('end_page_number');
            $table->double('page_number');
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
        Schema::dropIfExists('lesson_pages');
    }
}
