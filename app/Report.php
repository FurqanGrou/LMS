<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;

class Report extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];
    protected $table = 'reports';

    protected static function booted()
    {
        static::created(function(Report $report) {

            if (checkAbleToUpdateMonthlyScores($report)){
                $current_month_year = Carbon::today()->format('Y-m');
                $report_month_year = $report->created_at->format('Y-m');

                if ($current_month_year == $report_month_year){
                    $student_path = getStudentPath($report->student_id);

                    $new_lessons_not_listened = getLessonsNotListenedCount($report->student_id);
                    $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report->student_id);
                    $daily_revision_not_listened = getDailyRevisionNotListenedCount($report->student_id);
                    $absence_excuse_days = getAbsenceCount($report->student_id, -2);
                    $absence_unexcused_days = getAbsenceCount($report->student_id, -5);

                    DB::table('monthly_scores')->updateOrInsert(
                        [
                            'user_id' => $report->student_id,
                            'month_year' => $report->created_at->format('Y-m'),
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
                }else{
                    $month = $report->created_at->format('m');
                    $month_year = $report->created_at->format('Y-m');
                    $student_path = getStudentPath($report->student_id, $month_year);

                    $new_lessons_not_listened = getLessonsNotListenedCount($report->student_id, $month);
                    $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report->student_id, $month);
                    $daily_revision_not_listened = getDailyRevisionNotListenedCount($report->student_id, $month);
                    $absence_excuse_days = getAbsenceCount($report->student_id, -2, $month);
                    $absence_unexcused_days = getAbsenceCount($report->student_id, -5, $month);

                    DB::table('monthly_scores')->updateOrInsert(
                        [
                            'user_id' => $report->student_id,
                            'month_year' => $report->created_at->format('Y-m'),
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

        });

        static::updated(function(Report $report) {
            if ($report->wasChanged('FIELD_NAME')) {
                $student_reports = $report->student->reports()->orderBy('created_at', 'desc')->skip(1)->take(5)->get()->where('absence', '=', '-5');
                if ($student_reports->count() >= 5){
                    foreach ($student_reports as $row){
                        DropoutStudent::query()->updateOrCreate([
                            'report_id' => $row->id,
                            'student_id' => $report->student->id,
                        ],
                        [
                            'report_id' => $row->id,
                            'student_id' => $report->student->id,
                            'status' => '0'
                        ]);
                    }
//                $report->student->update([
//                    'internal_status' => 0
//                ]);
                }else{
                    // add student internal_status in users table
//                $report->student->update([
//                    'internal_status' => 1
//                ]);
                }
            }
        });

//        static::updated(function(Report $report) {
//
//            $current_month_year = Carbon::today()->format('Y-m');
//            $report_month_year = $report->created_at->format('Y-m');
//
//            if ($current_month_year == $report_month_year){
//                $student_path = getStudentPath($report->student_id);
//
//                $new_lessons_not_listened = getLessonsNotListenedCount($report->student_id);
//                $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report->student_id);
//                $daily_revision_not_listened = getDailyRevisionNotListenedCount($report->student_id);
//                $absence_excuse_days = getAbsenceCount($report->student_id, -2);
//                $absence_unexcused_days = getAbsenceCount($report->student_id, -5);
//
//                DB::table('monthly_scores')->updateOrInsert(
//                    [
//                        'user_id' => $report->student_id,
//                        'month_year' => $report->created_at->format('Y-m'),
//                    ],
//                    [
//                        'path' => $student_path,
//                        'new_lessons_not_listened' => $new_lessons_not_listened,
//                        'last_five_pages_not_listened' => $last_five_pages_not_listened,
//                        'daily_revision_not_listened' => $daily_revision_not_listened,
//                        'absence_excuse_days' => $absence_excuse_days,
//                        'absence_unexcused_days' => $absence_unexcused_days,
//                        'avg' => 100 + (
//                                ($new_lessons_not_listened * -getPathDefaultGrade($student_path, 'new_lesson')) +
//                                ($last_five_pages_not_listened * -getPathDefaultGrade($student_path, 'last_5_pages')) +
//                                ($daily_revision_not_listened * -getPathDefaultGrade($student_path, 'daily_revision')) +
//                                ($absence_excuse_days * -2) +
//                                ($absence_unexcused_days * -5)
//                            ),
//                    ]
//                );
//
//            }else{
//                $month = $report->created_at->format('m');
//                $month_year = $report->created_at->format('Y-m');
//                $student_path = getStudentPath($report->student_id, $month_year);
//
//                $new_lessons_not_listened = getLessonsNotListenedCount($report->student_id, $month);
//                $last_five_pages_not_listened = getLastFivePagesNotListenedCount($report->student_id, $month);
//                $daily_revision_not_listened = getDailyRevisionNotListenedCount($report->student_id, $month);
//                $absence_excuse_days = getAbsenceCount($report->student_id, -2, $month);
//                $absence_unexcused_days = getAbsenceCount($report->student_id, -5, $month);
//
//                DB::table('monthly_scores')->updateOrInsert(
//                    [
//                        'user_id' => $report->student_id,
//                        'month_year' => $report->created_at->format('Y-m'),
//                    ],
//                    [
//                        'new_lessons_not_listened' => $new_lessons_not_listened,
//                        'last_five_pages_not_listened' => $last_five_pages_not_listened,
//                        'daily_revision_not_listened' => $daily_revision_not_listened,
//                        'absence_excuse_days' => $absence_excuse_days,
//                        'absence_unexcused_days' => $absence_unexcused_days,
//                        'avg' => 100 + (
//                                ($new_lessons_not_listened * -getPathDefaultGrade($student_path, 'new_lesson')) +
//                                ($last_five_pages_not_listened * -getPathDefaultGrade($student_path, 'last_5_pages')) +
//                                ($daily_revision_not_listened * -getPathDefaultGrade($student_path, 'daily_revision')) +
//                                ($absence_excuse_days * -2) +
//                                ($absence_unexcused_days * -5)
//                            ),
//                    ]
//                );
//            }
//        });
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function dropoutStudents()
    {
        return $this->hasMany(DropoutStudent::class, 'report_id');
    }

}
