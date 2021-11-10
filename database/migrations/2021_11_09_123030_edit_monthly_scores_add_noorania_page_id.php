<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Driver;

class EditMonthlyScoresAddNooraniaPageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_scores', function (Blueprint $table) {
            $table->integer('lesson_page_id')->nullable()->default(null)->change();
            $table->integer('noorania_page_id')->nullable()->default(null)->after('lesson_page_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
