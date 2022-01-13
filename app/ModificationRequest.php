<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModificationRequest extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

}
