<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToColumnToMonthlyScoresFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_scores_files', function (Blueprint $table) {
            $table->string('email_to')->default('lmsfurqan1@gmail.com')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('column_to_monthly_scores_files', function (Blueprint $table) {
            //
        });
    }
}
