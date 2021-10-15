<?php

use Illuminate\Database\Seeder;
use App\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'website_title' => 'مسباح',
            'address' => 'عنوان تجريبي',
            'email' => 'info@msbah.com',
            'administration_phone' => '0595246076',
            'support_phone' => '2136684',
            'about_us' => 'نص من نحن تجريبي',
            'terms_conditions' => 'شروط واحكام تجريبية',
        ]);
    }
}
