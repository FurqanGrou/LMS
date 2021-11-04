<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $guarded = [];

    public function lessonPages(){
        return $this->hasMany(LessonPage::class, "part_id");
    }
}
