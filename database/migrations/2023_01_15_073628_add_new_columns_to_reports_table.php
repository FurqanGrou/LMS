<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('sitting_status', ['0', '1'])->default('1')->after('date');
            $table->enum('camera_status', ['0', '1'])->default('1')->after('sitting_status');
            $table->string('entry_time')->nullable()->after('camera_status');
            $table->string('exit_time')->nullable()->after('entry_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
        });
    }
}
