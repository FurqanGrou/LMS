<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(AdminSeeder::class);
//         $this->call(UserSeeder::class);
//         $this->call(InsertPreviousMonthlyScoresSeeder::class);
//         $this->call(ReportSeeder::class);
//         $this->call(HolidaySeeder::class);
//         $this->call(DropoutStudentsSeeder::class);
         $this->call(AssignHolidaysSeeder::class);
    }
}
