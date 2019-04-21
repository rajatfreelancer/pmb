<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return redirect('/admin');

});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::middleware('auth.admin')->group(function () {
        Route::get('home', 'DashboardController@index')->name('dashboard');
        Route::resource('user', 'UserController');
        Route::resource('accounts', 'AccountController');
        Route::get('get-users-list', 'AccountController@getUsersList')->name('get.users.list');
        
        /*Route::get('/local', 'DashboardController@localBackup')->name('local.backup');*/
        Route::get('/local', 'DashboardController@runBackup')->name('local.backup');
        
        Route::post('get-duration', 'UserController@getDuration')->name('get.duration');
        Route::post('get-denomination', 'UserController@getDenominaton')->name('get.denomination');
        
    });

});


