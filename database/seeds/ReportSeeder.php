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
        $month = 12;
        $students = \App\User::query()->get();

        foreach ($students as $student){
            $student->update([
                "path" => trim($student->path),
            ]);
        }

        foreach ($students as $key => $student){
            $report = \App\Report::where('student_id', '=', $student->id)
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', 2021)
                ->first();

            if (!is_null($report)){
                echo $student->id . "\n";

                $month_year = $report->created_at->format('Y-m');
                $student_path = getStudentPath($report->student_id);

                $new_lessons_not_listened = getLessonsNotListenedCount($student->id, $month);
                $last_five_pages_not_listened = getLastFivePagesNotListenedCount($student->id, $month);
                $daily_revision_not_listened = getDailyRevisionNotListenedCount($student->id, $month);
                $absence_excuse_days = getAbsenceCount($student->id, -2, $month);
                $absence_unexcused_days = getAbsenceCount($student->id, -5, $month);

                DB::table('monthly_scores')->updateOrInsert(
                    [
                        'user_id' => $student->id,
                        'month_year' => "2021-" . $month,
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
