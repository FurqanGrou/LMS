<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    public function Form()
    {
        return $this->belongsTo(Form::class);
    }
}
