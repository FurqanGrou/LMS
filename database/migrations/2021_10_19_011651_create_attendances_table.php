<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('last_4_id');
            $table->unsignedBigInteger('employee_number');
            $table->string('full_name');
            $table->enum('section', ['male', 'female']);
            $table->enum('type', ['login', 'logout']);
            $table->string('period');
            $table->integer('teacher_id');
            $table->string('action_code')->unique();
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
        Schema::dropIfExists('attendances');
    }
}
