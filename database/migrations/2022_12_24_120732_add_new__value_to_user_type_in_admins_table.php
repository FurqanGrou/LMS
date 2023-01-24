<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNewValueToUserTypeInAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            DB::statement("ALTER TABLE `admins` CHANGE `user_type` `user_type` ENUM('super_admin','furqan_group','iksab', 'egypt') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_type_in_admins', function (Blueprint $table) {
            DB::statement("ALTER TABLE `admins` CHANGE `user_type` `user_type` ENUM('super_admin','furqan_group','iksab') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }
}
