<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthlyResultsColumnsToMonthlyScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_scores', function (Blueprint $table) {
            $table->integer('final_month_lesson_id')->nullable()->after('noorania_page_id');
            $table->integer('final_month_aya_id')->nullable()->after('final_month_lesson_id');
            $table->integer('final_semester_lesson_id')->nullable()->after('final_month_aya_id');
            $table->integer('final_semester_aya_id')->nullable()->after('final_semester_lesson_id');
            $table->integer('final_year_lesson_id')->nullable()->after('final_semester_aya_id');
            $table->integer('final_year_aya_id')->nullable()->after('final_year_lesson_id');

            $table->date('seal_quran_date')->nullable()->after('final_year_aya_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_scores', function (Blueprint $table) {
            //
        });
    }
}
