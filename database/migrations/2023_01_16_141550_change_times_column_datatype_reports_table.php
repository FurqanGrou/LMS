<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTimesColumnDatatypeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Report::query()->update([
            'entry_time' => null,
            'exit_time' => null,
        ]);

        Schema::table('reports', function (Blueprint $table) {
            $table->time('entry_time')->nullable()->change();
            $table->time('exit_time')->nullable()->change();
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
