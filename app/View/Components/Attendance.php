<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Attendance extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if (auth()->guard('admin_web')->check()){
            $route = route('admins.attendance.store');
        }else{
            $route = route('teachers.attendance.store');
        }

        return view('components.attendance',  ['route' => $route]);
    }
}
