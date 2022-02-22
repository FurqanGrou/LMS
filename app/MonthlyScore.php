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
}
