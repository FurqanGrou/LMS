<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $guarded = [];
    protected $appends = ['period_title'];

    public function users()
    {
        return $this->hasMany(User::class, 'class_number');
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'classes_teachers');
    }

    public function getPeriodTitleAttribute()
    {
        $title = '';
        switch ($this->period){
            case 1: $title = 'الفترة الصباحية';
                break;
            case 2: $title = 'الفترة المسائية الأولى';
                break;
            case 3: $title = 'الفترة المسائية الثانية';
                break;
            case 4: $title = 'الفترة المسائية الثالثة';
                break;
            case 5: $title = 'الفترة المسائية الرابعة';
                break;
        }
        return $title;
    }

}
