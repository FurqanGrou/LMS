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
        $month = 10;
        Excel::import(new PreviousMonthlyScoresImport($month), 'public/males-10.xlsx');

        dd('Males - 10 Done');
    }

}