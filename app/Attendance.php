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
        return $this->asDateTime($value)->format('Y-m-d H:i:s');
    }

    protected static function booted()
    {
        static::creating(function (Attendance $attendance) {
            $attendance->created_at = Carbon::now()->addHours(8);
        });
    }

}
