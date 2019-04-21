<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Article;
use App\User;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

use Dawson\Youtube\Facades\Youtube;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function runBackup(){
        // Please check ny command line.
        \Artisan::call("backup:run --only-db");
    }
    public function txtSendSms(){
        return view('admin.txtsms.txtsms');
    }


    public function sendSms(Request $request)
    {

        $result = Admin::sendSms($request->number, $request->message);
        // Process your response here
        echo $result;
        die;
    }
}
