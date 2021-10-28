<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestComplaintBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_complaint_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->string('complaint_type')->nullable();
            $table->string('name');
            $table->string('subject');
            $table->text('details')->nullable();
            $table->integer('teacher_id');
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
        Schema::dropIfExists('suggest_complaint_boxes');
    }
}
