<?php

namespace App\Http\Controllers;


//use Alaouy\Youtube\Facades\Youtube;
use App\Admin;
use App\User;
use Dawson\Youtube\Facades\Youtube;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        //return view('welcome');
    }
}
