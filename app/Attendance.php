<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return $this->asDateTime($value)->timezone('Asia/Riyadh')->format('Y-m-d H:i:s');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

}
