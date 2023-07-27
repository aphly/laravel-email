<?php


use Illuminate\Support\Facades\Route;

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
Route::get('email/send', 'Aphly\LaravelEmail\Controllers\Front\EmailController@send');
Route::post('email/send', 'Aphly\LaravelEmail\Controllers\Front\EmailController@send');

Route::middleware(['web'])->group(function () {

    Route::prefix('email_admin')->middleware(['managerAuth'])->group(function () {
        Route::middleware(['rbac'])->group(function () {
            Route::get('email/index', 'Aphly\LaravelEmail\Controllers\Admin\EmailController@index');
            Route::get('email/detail', 'Aphly\LaravelEmail\Controllers\Admin\EmailController@detail');
            Route::post('email/del', 'Aphly\LaravelEmail\Controllers\Admin\EmailController@del');

            $route_arr = [
                ['site','\EmailSiteController']
            ];

            foreach ($route_arr as $val){
                Route::get($val[0].'/index', 'Aphly\LaravelEmail\Controllers\Admin'.$val[1].'@index');
                Route::get($val[0].'/form', 'Aphly\LaravelEmail\Controllers\Admin'.$val[1].'@form');
                Route::post($val[0].'/save', 'Aphly\LaravelEmail\Controllers\Admin'.$val[1].'@save');
                Route::post($val[0].'/del', 'Aphly\LaravelEmail\Controllers\Admin'.$val[1].'@del');
            }
            Route::match(['get','post'],'email/test', 'Aphly\LaravelEmail\Controllers\Admin\EmailController@test');
        });
    });

});
