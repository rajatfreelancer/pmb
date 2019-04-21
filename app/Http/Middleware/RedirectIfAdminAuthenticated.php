<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = Auth::guard('admins');
        Log::info('dddd');
        if ($auth->check()) {

            Log::info('dddd6666666666');
            return redirect('/admin/home');
        }

        Log::info('dddd77777777');

        return $next($request);
    }
}
