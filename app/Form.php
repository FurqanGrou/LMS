<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{


    public function FormInputs()
    {
        return $this->hasMany(FormInput::class);
    }
}
