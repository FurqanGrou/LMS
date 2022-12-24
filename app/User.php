<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use Notifiable;
    use \OwenIt\Auditing\Auditable;


    protected $appends = ['avg'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_number');
    }

    public function monthlyScores($month_year = null)
    {

        $month_year = request()->date_filter;

        if (env('ENABLE_PREVIOUS_MONTH')){
            if ((\Request::route()->getName() == 'teachers.report.table' || \Request::route()->getName() == 'admins.report.table') && !request()->date_filter){
                $month_year = date('Y') . '-' . date('m');
            }
        }else{
            if(empty($month_year)){
                $month_year = date('Y') . '-' . date('m');
            }
        }

        return $this->hasMany(MonthlyScore::class, 'user_id')->where('month_year', '=', $month_year);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'student_id');
    }

    public function getAvgAttribute()
    {
        return $this->monthlyScores()->first()->avg ?? 0;
    }

    public function dropoutStudents()
    {
        return $this->hasMany(DropoutStudent::class, 'student_id');
    }

    public function planForcasts()
    {
        $month_year = request()->date_filter;

        if(empty($month_year)){
            $month_year = date('Y') . '-' . date('m');
        }

        return $this->hasMany(PlanForcast::class, 'user_id')->where('month_year', '=', $month_year);;
    }

}
