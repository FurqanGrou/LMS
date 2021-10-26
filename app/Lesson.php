<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Lesson extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'lessons';
    protected $guarded = [];

}
