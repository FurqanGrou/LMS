<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuggestComplaintBox extends Model
{
    protected $guarded = [];
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function getRequestTypeAttribute($value)
    {
        return $value == "suggest" ? 'اقتراح' : 'شكوى';
    }

}
