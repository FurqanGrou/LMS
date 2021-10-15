<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'class_number');
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'classes_teachers');
    }


}
