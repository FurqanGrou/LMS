<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'value', 'form_id', 'request_code'
    ];

    public static function setValue($name, $value, $form_id, $uniqid)
    {
        static::query()->create([
            'name' => $name,
            'value' => $value,
            'form_id' => $form_id,
            'request_code' => $uniqid,
        ]);
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->asDateTime($value)->format('Y-m-d');
    }

}
