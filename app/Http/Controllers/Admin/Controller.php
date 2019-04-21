<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $auth_user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // $common_data = [];
            // $user = Auth::user();
            // $this->auth_user = $user;

            $common_data['auth_user'] = \Auth::guard('admins')->user();
            $this->auth_user = \Auth::guard('admins')->user();

            \Illuminate\Support\Facades\View::share($common_data);

            return $next($request);
        });
    }
}
