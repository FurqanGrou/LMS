<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $month_year = '2021-12';

        $currentMonth = substr($month_year, -2);
        $currentYear  = substr($month_year, 0, 4);

        $students = \App\User::query()->get();

        foreach ($students as $student){
            $student->update([
                "path" => trim($student->path),
            ]);
        }

        foreach ($students as $key => $student){
            $report = \App\Report::where('student_id', '=', $student->id)
                ->whereMonth('created_at', '=', $currentMonth)
                ->whereYear('created_at', '=', $currentYear)
                ->first();

            if (!is_null($report)){
                echo $student->id . "\n";

                $month_year   = $report->created_at->format('Y-m');
                $student_path = getStudentPath($report->student_id, $month_year);

                $new_lessons_not_listened = getLessonsNotListenedCount($student->id, $month_year);
                $last_five_pages_not_listened = getLastFivePagesNotListenedCount($student->id, $month_year);
                $daily_revision_not_listened = getDailyRevisionNotListenedCount($student->id, $month_year);
                $absence_excuse_days = getAbsenceCount($student->id, -2, $month_year);
                $absence_unexcused_days = getAbsenceCount($student->id, -5, $month_year);

                DB::table('monthly_scores')->updateOrInsert(
                    [
                        'user_id' => $student->id,
                        'month_year' => $month_year,
                    ],
                    [
                        'path' => $student_path,
                        'new_lessons_not_listened' => $new_lessons_not_listened,
                        'last_five_pages_not_listened' => $last_five_pages_not_listened,
                        'daily_revision_not_listened' => $daily_revision_not_listened,
                        'absence_excuse_days' => $absence_excuse_days,
                        'absence_unexcused_days' => $absence_unexcused_days,
                        'avg' => 100 + (
                                ($new_lessons_not_listened * -getPathDefaultGrade($student_path, 'new_lesson')) +
                                ($last_five_pages_not_listened * -getPathDefaultGrade($student_path, 'last_5_pages')) +
                                ($daily_revision_not_listened * -getPathDefaultGrade($student_path, 'daily_revision')) +
                                ($absence_excuse_days * -2) +
                                ($absence_unexcused_days * -5)
                            ),
                    ]
                );
            }
        }

    }
}
