<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::today();
        $today_created_at = Carbon::createFromDate($today->year, $today->month, $today->day)->format('Y-m-d H:i:s');
        $today_date = Carbon::createFromDate($today->year, $today->month, $today->day)->format('l d-m-Y');

        $users = \App\User::all();
        foreach ($users as $user){
            $class = \App\Classes::where('class_number', '=', $user->class_number)->first();
            \App\Report::create([
                    'date' => $today_date,
                    'new_lesson' => 'test',
                    'student_id' => $user->id,
                    'class_id' => $class->id,
                    'created_at' => $today_created_at,
                ]
            );
        }

    }
}
