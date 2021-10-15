<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_title');
            $table->string('address');
            $table->string('email');
            $table->string('administration_phone');
            $table->string('support_phone');
            $table->text('about_us');
            $table->text('terms_conditions');
            $table->string('payment_api_url');
            $table->string('payment_api_token_key');
            $table->string('sms_account_sid');
            $table->string('sms_auth_token');
            $table->string('sms_verify_sid');
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
        Schema::dropIfExists('settings');
    }
}
