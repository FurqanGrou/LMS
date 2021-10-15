<?php

namespace App\Http\Middleware;

use Closure;

class CheckTeacherStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->guard('teacher_web')->user()->status){
            auth()->guard('teacher_web')->logout();
            return redirect()->route('teachers.teacher.index');
        }

        return $next($request);
    }
}
