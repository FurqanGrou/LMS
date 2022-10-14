<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyScore extends Model
{
    protected $table = 'monthly_scores';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function lessonPage(){
        if (getStudentPath(request()->student_id, request()->date_filter) == "قسم الهجاء"){
            return $this->belongsTo(NooraniaPage::class, 'noorania_page_id');
        }
        return $this->belongsTo(LessonPage::class, 'lesson_page_id');
    }

    public function finalMonthLesson(){
        if (getStudentPath(request()->student_id, request()->date_filter) == "قسم الهجاء"){
            return $this->belongsTo(NooraniaPage::class, 'final_month_lesson_id');
        }
        return $this->belongsTo(Lesson::class, 'final_month_lesson_id');
    }

    public function finalSemesterLesson(){
        if (getStudentPath(request()->student_id, request()->date_filter) == "قسم الهجاء"){
            return $this->belongsTo(NooraniaPage::class, 'final_month_lesson_id');
        }
        return $this->belongsTo(Lesson::class, 'final_semester_lesson_id');
    }

    public function finalYearLesson(){
        if (getStudentPath(request()->student_id, request()->date_filter) == "قسم الهجاء"){
            return $this->belongsTo(NooraniaPage::class, 'final_month_lesson_id');
        }
        return $this->belongsTo(Lesson::class, 'final_year_lesson_id');
    }
}
