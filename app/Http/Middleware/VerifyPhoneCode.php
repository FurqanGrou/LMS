<?php

namespace App\Http\Middleware;

use App\Traits\APIResponser;
use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyPhoneCode
{

    use APIResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::guard('api')->check()){
            if ( is_null(Auth::guard('api')->user()->email_verified_at) ){
                return $this->failsResponse('401', ['Account not verified']);
            }
        }

        return $next($request);
    }
}
