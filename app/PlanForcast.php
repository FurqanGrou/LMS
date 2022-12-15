<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanForcast extends Model
{
    protected $table = 'plan_forcasts';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
