<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('last_4_id');
            $table->dropColumn('employee_number');
            $table->dropColumn('full_name');
            $table->dropColumn('action_code');
            $table->dropColumn('section');

            $table->integer('admin_id')->nullable()->after('teacher_id');
            $table->integer('teacher_id')->nullable()->change();
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
