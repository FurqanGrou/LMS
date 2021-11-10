<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NooraniaPage extends Model
{
    protected $guarded = [];

    public function monthlyScores(){
        return $this->hasMany(MonthlyScore::class, 'noorania_page_id');
    }

}
