<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceAbsenceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_absence_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->date('date_excuse');
            $table->text('reason_excuse');
            $table->string('additional_attachments_path')->nullable();
            $table->string('duration_delay')->nullable();
            $table->string('exit_time')->nullable();
            $table->integer('teacher_id');
            $table->json('class_number');
            $table->integer('spare_teacher_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->date('available_to_date')->nullable();
            $table->boolean('is_overtime')->default(0);
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
        Schema::dropIfExists('attendance_absence_requests');
    }
}
