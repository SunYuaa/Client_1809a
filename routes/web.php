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


//test
Route::get('/test/test/','Test\TestController@test');
Route::get('/test/en/','Test\TestController@en');
Route::get('/test/de/','Test\TestController@de');

Route::get('/test/sendSec/','Test\TestController@sendSec');
Route::get('/test/unSec/','Test\TestController@unSec');
Route::get('/test/testSign/','Test\TestController@testSign');

//exam 注册登录
Route::get('/login/register/','Exam\ThirteenController@register');
Route::post('/login/regDo/','Exam\ThirteenController@regDo');
Route::get('/login/login/','Exam\ThirteenController@login');


