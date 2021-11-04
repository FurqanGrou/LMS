<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('month_year');
            $table->integer('new_lessons_not_listened')->default(0);
            $table->integer('last_five_pages_not_listened')->default(0);
            $table->integer('daily_revision_not_listened')->default(0);
            $table->integer('absence_excuse_days')->default(0);
            $table->integer('absence_unexcused_days')->default(0);
            $table->integer('lesson_page_id')->default(-1);
            $table->integer('avg')->default(0);
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
        Schema::dropIfExists('monthly_scores');
    }
}
