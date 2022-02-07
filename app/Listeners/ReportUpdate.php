<?php

namespace App\Listeners;

use App\Events\ReportUpdated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class ReportUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReportUpdated  $event
     * @return void
     */
    public function handle(ReportUpdated $event)
    {
        $report = $event->report;

        $current_month_year = Carbon::today()->format('Y-m');
        $report_month_year = $report['created_at'];

        if ($current_month_year == $report_month_year){
            $student_path = getStudentPath($report['student_id']);

            $new_lessons_not_listened = getLessonsNotListenedCount($report['student_id']);
            $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report['student_id']);
            $daily_revision_not_listened = getDailyRevisionNotListenedCount($report['student_id']);
            $absence_excuse_days = getAbsenceCount($report['student_id'], -2);
            $absence_unexcused_days = getAbsenceCount($report['student_id'], -5);

            DB::table('monthly_scores')->updateOrInsert(
                [
                    'user_id'    => $report['student_id'],
                    'month_year' => $report['created_at'],
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
                    'class_number' => $report['class_number']
                ]
            );

        }else{
            $month_year   = $report['created_at'];
            $student_path = getStudentPath($report['student_id'], $month_year);

            $new_lessons_not_listened = getLessonsNotListenedCount($report['student_id'], $month_year);
            $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report['student_id'], $month_year);
            $daily_revision_not_listened = getDailyRevisionNotListenedCount($report['student_id'], $month_year);
            $absence_excuse_days = getAbsenceCount($report['student_id'], -2, $month_year);
            $absence_unexcused_days = getAbsenceCount($report['student_id'], -5, $month_year);

            DB::table('monthly_scores')->updateOrInsert(
                [
                    'user_id'    => $report['student_id'],
                    'month_year' => $report['created_at'],
                ],
                [
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
