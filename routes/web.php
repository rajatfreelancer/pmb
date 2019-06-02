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
        
        Route::get('get-admins-list/{id?}', 'AccountController@getAdminsList')->name('get.admins.list');
        Route::get('get-users-list/{id?}', 'AccountController@getUsersList')->name('get.users.list');
        Route::get('accounts', 'AccountController@accounts')->name('accounts');

        Route::get('accounts-fd', 'AccountController@accountsFd')->name('accounts.fd');
        Route::get('ajax-get-data-fd', 'AccountController@getDataFD')->name('ajax.get.data.fd');
        Route::get('accounts-savings', 'AccountController@accountsSavings')->name('accounts.savings');
        Route::get('ajax-get-data-savings', 'AccountController@getDataSavings')->name('ajax.get.data.savings');
        Route::get('accounts-mis', 'AccountController@accountsMis')->name('accounts.mis');
        Route::get('ajax-get-data-mis', 'AccountController@getDataMis')->name('ajax.get.data.mis');
        Route::get('accounts-mature/{id}', 'AccountController@mature')->name('accounts.mature');
        Route::post('accounts-mature/{id}/store', 'AccountController@matureStore')->name('accounts.mature.store');

        Route::get('ajax-get-data', 'AccountController@getData')->name('ajax.get.data');
        Route::get('print_passbook/{id}', 'AccountController@printPassbook')->name('print.passbook');
        Route::get('print_fd/{id}', 'AccountController@printFd')->name('print.fd');
        Route::get('print_transactions/{id}', 'AccountController@printTransactions')->name('print.transactions');
        Route::get('account_export/{type?}', 'AccountController@export')->name('account.export');
        Route::post('account_export/{type?}', 'AccountController@export')->name('account.export.post');
         Route::get('transactions_export/{type?}', 'TransactionController@export')->name('transactions.export');
        Route::post('transactions_export/{type?}', 'TransactionController@export')->name('transactions.export.post');

        /*Route::get('/local', 'DashboardController@localBackup')->name('local.backup');*/
        Route::get('/local', 'DashboardController@runBackup')->name('local.backup');
        
        Route::post('get-duration', 'UserController@getDuration')->name('get.duration');
        Route::post('get-denomination', 'UserController@getDenominaton')->name('get.denomination');

        Route::resource('staff', 'AdminController');
        Route::get('/txt-send-sms/', 'DashboardController@txtSendSms')->name('txt.send.sms');
        Route::post('/send_sms', 'DashboardController@sendSms')->name('send.sms');

        Route::resource('transactions', 'TransactionController');
        
        Route::get('/transactions-data', 'TransactionController@getData')->name('transactions.ajax.get.data');
        Route::get('/get-accounts-list/{id?}', 'TransactionController@getAccountList')->name('get.accounts.list');
        
    });

});


