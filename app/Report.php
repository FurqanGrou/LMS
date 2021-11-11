<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Report extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];
    protected $table = 'reports';

    protected static function booted()
    {
        static::created(function(Report $report) {

            MonthlyScore::query()->updateOrCreate(
                [
                    'user_id' => $report->student_id,
                    'month_year' => $report->created_at->format('Y-m'),
                ],
                [
                    'new_lessons_not_listened' => getLessonsNotListenedCount($report->student_id),
                    'last_five_pages_not_listened' => getLastFivePagesNotListenedCount($report->student_id),
                    'daily_revision_not_listened' => getDailyRevisionNotListenedCount($report->student_id),
                    'absence_excuse_days' => getAbsenceCount($report->student_id, -2),
                    'absence_unexcused_days' => getAbsenceCount($report->student_id, -5),
                    'avg' => 100 + (
                            (getLessonsNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'new_lesson')) +
                            (getLastFivePagesNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'last_5_pages')) +
                            (getDailyRevisionNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'daily_revision')) +
                            (getAbsenceCount($report->student_id, -2) * -2) +
                            (getAbsenceCount($report->student_id, -5) * -5)
                        ),
                ]
            );
        });

        static::updated(function(Report $report) {

            MonthlyScore::query()->updateOrCreate(
                [
                    'user_id' => $report->student_id,
                    'month_year' => $report->created_at->format('Y-m'),
                ],
                [
                    'new_lessons_not_listened' => getLessonsNotListenedCount($report->student_id),
                    'last_five_pages_not_listened' => getLastFivePagesNotListenedCount($report->student_id),
                    'daily_revision_not_listened' => getDailyRevisionNotListenedCount($report->student_id),
                    'absence_excuse_days' => getAbsenceCount($report->student_id, -2),
                    'absence_unexcused_days' => getAbsenceCount($report->student_id, -5),
                    'avg' => 100 + (
                            (getLessonsNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'new_lesson')) +
                            (getLastFivePagesNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'last_5_pages')) +
                            (getDailyRevisionNotListenedCount($report->student_id) * -getPathDefaultGrade(getStudentPath($report->student_id), 'daily_revision')) +
                            (getAbsenceCount($report->student_id, -2) * -2) +
                            (getAbsenceCount($report->student_id, -5) * -5)
                        ),
                ]
            );
        });
    }
}
