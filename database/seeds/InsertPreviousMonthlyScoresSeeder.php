<?php

use App\Imports\UsersImport;
use App\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PreviousMonthlyScoresImport;

class InsertPreviousMonthlyScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month_year = '2021-12';
        $file_name = 'females-dec-12.xlsx';

        Excel::import(new PreviousMonthlyScoresImport($month_year), 'public/grades_previous_months/'.$file_name);

        echo "$file_name Done";
    }

}
