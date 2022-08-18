<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopTrackerEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_tracker_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nationality_no');
            $table->bigInteger('employee_no');
            $table->string('name');
            $table->enum('section', [1, 2]);
            $table->enum('type', [0, 1, 2]);
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
        Schema::dropIfExists('top_tracker_employees');
    }
}
