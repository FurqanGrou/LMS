<?php

use Illuminate\Database\Seeder;
use \App\Imports\AssignHolidayImport;
use Maatwebsite\Excel\Facades\Excel;

class AssignHolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new AssignHolidayImport(), 'public/males-31-5-22.xlsx');
    }
}
