<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClassesTeachers extends Model
{
    protected $table = 'classes_teachers';
//    protected $primaryKey = ['class_number', 'teacher_email'];
//    public $incrementing = false;
    protected $guarded = [];

}
