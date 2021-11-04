<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonPage extends Model
{
    protected $guarded = [];

    public function part(){
        return $this->belongsTo(Part::class);
    }

    public function monthlyScores(){
        return $this->hasMany(MonthlyScore::class, 'lesson_page_id');
    }
}
