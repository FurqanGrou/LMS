<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDatatypeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\User::query()->update([
            'login_time' => null,
            'exit_time' => null,
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->time('login_time')->nullable()->default(null)->change();
            $table->time('exit_time')->nullable()->default(null)->change();
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
