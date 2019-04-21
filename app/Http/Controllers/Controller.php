<?php

namespace App\Http\Controllers;

use App\LoginActivity;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $common_data = [];
            $user = Auth::user();
            $this->auth_user = $user;

            $common_data['auth_user'] = \Auth::user();
            \Illuminate\Support\Facades\View::share($common_data);
            //$this->updateLastVisit($request->header('User-Agent'));

            //Set Language
            $session_locale = session('locale');
            $session_locale_new = session('locale_new');

            if ($session_locale != "") {

                Log::info($session_locale);
                App::setLocale($session_locale);
            } else {
                if ($session_locale_new != "") {
// $request->session()->forget('locale_new');
                    App::setLocale($session_locale_new);
//session(['locale_new' => $session_locale ]);
                    $request->session()->forget('locale_new');
                    session(['locale' => $session_locale_new]);
                }else{
                    App::setLocale('en');
                }
            }

            $locale = App::getLocale();
      //  print_r($locale);exit;
         //   Log::info('set language');
      //      Log::info($locale);
//Set Language
            return $next($request);
        });
    }
}
