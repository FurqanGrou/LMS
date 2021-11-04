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
//        $today = Carbon::today();
//        $today_created_at = Carbon::createFromDate($today->year, $today->month, $today->day)->format('Y-m-d H:i:s');
//        $today_date = Carbon::createFromDate($today->year, $today->month, $today->day)->format('l d-m-Y');
//
//        $users = \App\User::all();
//        foreach ($users as $user){
//            $class = \App\Classes::where('class_number', '=', $user->class_number)->first();
//            \App\Report::create([
//                    'date' => $today_date,
//                    'new_lesson' => 'test',
//                    'student_id' => $user->id,
//                    'class_id' => $class->id,
//                    'created_at' => $today_created_at,
//                ]
//            );
//        }


        $students = \App\User::query()->get();
//        foreach ($students as $student){
//            $student->update([
//                "path" => trim($student->path),
//            ]);
//        }

        foreach ($students as $key => $student){
            $report = \App\Report::where('student_id', '=', $student->id)
//                ->whereMonth('created_at', '=', 11)
                ->whereMonth('created_at', '=', 10)
                ->whereYear('created_at', '=', 2021)
                ->first();

            if (!is_null($report)){
                echo $student->id . "\n";

                DB::table('monthly_scores')->updateOrInsert(
                    [
                        'user_id' => $student->id,
//                        'month_year' => "2021-11",
                        'month_year' => "2021-10",
                    ],
                    [
                        'new_lessons_not_listened' => getLessonsNotListenedCount($student->id, true),
                        'last_five_pages_not_listened' => getLastFivePagesNotListenedCount($student->id, true),
                        'daily_revision_not_listened' => getDailyRevisionNotListenedCount($student->id, true),
                        'absence_excuse_days' => getAbsenceCount($student->id, -2, true),
                        'absence_unexcused_days' => getAbsenceCount($student->id, -5, true),
                        'page_number' => 0,
                        'avg' => 100 + (
                                (getLessonsNotListenedCount($student->id, true) * -getPathDefaultGrade(getStudentPath($student->id), 'new_lesson')) +
                                (getLastFivePagesNotListenedCount($student->id, true) * -getPathDefaultGrade(getStudentPath($student->id), 'last_5_pages')) +
                                (getDailyRevisionNotListenedCount($student->id, true) * -getPathDefaultGrade(getStudentPath($student->id), 'daily_revision')) +
                                (getAbsenceCount($student->id, -2, true) * -2) +
                                (getAbsenceCount($student->id, -5, true) * -5)
                            ),
                    ]
                );
            }
        }

    }
}
