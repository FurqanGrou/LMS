<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropoutStudent extends Model
{
    protected $table = 'dropout_students';
    protected $fillable = ['report_id', 'student_id', 'status'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }

}
