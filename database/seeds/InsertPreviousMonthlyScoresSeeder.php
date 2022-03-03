<?php

use App\Imports\UsersImport;
use App\Report;
use App\Teacher;
use Carbon\Carbon;
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
        $day   = substr('2022-02-07', -2);
        $month = substr('2022-02-07', 5, 2);
        $year  = substr('2022-02-07', 0, 4);

        $date_from = new \DateTime('2022-02-07');
        $date_to  = new \DateTime('2022-02-10');
        $interval = $date_from->diff($date_to);
        $days     = $interval->format('%a');

        $students = User::query();
            $students = $students->whereIn('id', [1614])->get();

        foreach($students as $student){
            $date = Carbon::createFromDate($year, $month, $day);
            $last_report = Report::query()
                ->where('student_id', '=', $student->id)
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->latest()
                ->first();

            for($i = $day; $i <= $days+1; $i++){
                Report::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $date->format('l d-m-Y'),
                        'created_at' => $date->format('Y-m-d')
                    ],
                    [
                        'new_lesson' => $last_report->new_lesson ?? '-',
                        'new_lesson_from' => $last_report->new_lesson_from ?? '-',
                        'new_lesson_to' => $last_report->new_lesson_to ?? '-',
                        'last_5_pages' => $last_report->last_5_pages ?? '-',
                        'daily_revision' => $last_report->daily_revision ?? '-',
                        'daily_revision_from' => $last_report->daily_revision_from ?? '-',
                        'daily_revision_to' => $last_report->daily_revision_to ?? '-',
                        'number_pages' => $last_report->number_pages ?? '-',
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => '-',
                        'daily_revision_grade' => '-',
                        'behavior_grade' => '-',
                        'notes_to_parent' => 'دوام 3 أيام',
                        'absence' => '-1',
                        'total' => 0,
                        'mail_status' => 0,
                        'class_number' => $student->class_number,
                    ]
                );

                $date->addDay();
            }
        }
    }

}
