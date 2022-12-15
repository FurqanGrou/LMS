<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuranLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quran_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('serial_number');
            $table->text('aya');
            $table->text('aya_normal');
            $table->string('lesson');
            $table->integer('page');
            $table->integer('part');
            $table->integer('aya_num');
            $table->float('aya_length');
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
        Schema::dropIfExists('quran_lines');
    }
}
