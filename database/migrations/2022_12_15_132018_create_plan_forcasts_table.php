<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanForcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_forcasts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('month_year')->index();
            $table->string('month_lesson');
            $table->integer('month_aya');
            $table->string('semester_lesson');
            $table->integer('semester_aya');
            $table->string('year_lesson');
            $table->integer('year_aya');
            $table->string('seal_quran_date');
            $table->timestamps();

            $table->unique(['user_id', 'month_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_forcasts');
    }
}
