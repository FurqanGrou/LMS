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
        $month = 11;
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

                DB::table('monthly_scores')->updateOrInsert(
                    [
                        'user_id' => $student->id,
                        'month_year' => "2021-" . $month,
                    ],
                    [
                        'new_lessons_not_listened' => getLessonsNotListenedCount($student->id, $month),
                        'last_five_pages_not_listened' => getLastFivePagesNotListenedCount($student->id, $month),
                        'daily_revision_not_listened' => getDailyRevisionNotListenedCount($student->id, $month),
                        'absence_excuse_days' => getAbsenceCount($student->id, -2, $month),
                        'absence_unexcused_days' => getAbsenceCount($student->id, -5, $month),
                        'avg' => 100 + (
                                (getLessonsNotListenedCount($student->id, $month) * -getPathDefaultGrade(getStudentPath($student->id), 'new_lesson')) +
                                (getLastFivePagesNotListenedCount($student->id, $month) * -getPathDefaultGrade(getStudentPath($student->id), 'last_5_pages')) +
                                (getDailyRevisionNotListenedCount($student->id, $month) * -getPathDefaultGrade(getStudentPath($student->id), 'daily_revision')) +
                                (getAbsenceCount($student->id, -2, $month) * -2) +
                                (getAbsenceCount($student->id, -5, $month) * -5)
                            ),
                    ]
                );
            }
        }

    }
}
