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
        return $this->belongsTo(LessonPage::class);
    }

}
