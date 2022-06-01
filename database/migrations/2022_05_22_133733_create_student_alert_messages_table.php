<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAlertMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_alert_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('alert_message_id');
            $table->text('message_reply')->nullable();
            $table->enum('status', [1, 0]);

            $table->timestamps();

            $table->unique(['student_id', 'alert_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_alert_messages');
    }
}
