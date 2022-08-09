<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NoteParent extends Model
{
    protected $guarded = [];

//    public function getUpdatedAtAttribute($date)
//    {
//        return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
//    }
}
