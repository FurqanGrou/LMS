<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->enum('type', ['text', 'select', 'date', 'int','hidden'])->nullable();
            $table->string('attr')->nullable();
            $table->string('default_value')->nullable();
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
        Schema::dropIfExists('form_inputs');
    }
}
