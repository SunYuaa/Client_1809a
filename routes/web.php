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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//test
Route::get('/test/test/','Test\TestController@test');
Route::get('/test/en/','Test\TestController@en');
Route::get('/test/de/','Test\TestController@de');

Route::get('/test/sendSec/','Test\TestController@sendSec');
Route::get('/test/unSec/','Test\TestController@unSec');
Route::get('/test/testSign/','Test\TestController@testSign');

Route::get('/test/demo/','Test\DemoController@demo');

//exam 注册登录
Route::get('/login/register/','Exam\ThirteenController@register');
Route::post('/login/regDo/','Exam\ThirteenController@regDo');
Route::post('/login/login/','Exam\ThirteenController@login');

//exam 5.20
Route::middleware('auth')->group(function(){
    Route::get('/legal/reg/','Exam\LegalController@register');
    Route::post('/legal/regDo/','Exam\LegalController@regDo');
});
Route::get('/legal/checkStatus/','Exam\LegalController@checkStatus');

Route::middleware('RequestNum')->group(function(){
    Route::get('/legal/token/','Exam\LegalController@token');
    Route::get('/legal/getUserIp/','Exam\LegalController@getUserIp');
    Route::get('/legal/getUA/','Exam\LegalController@getUA');
    Route::get('/legal/getUserInfo/','Exam\LegalController@getUserInfo');
});




