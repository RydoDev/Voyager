<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/v1/register', 'AccountController@Register');
Route::post('/api/v1/login', 'AccountController@Login');
Route::post('/api/v1/checksession', 'AccountController@CheckSession');
Route::post('/api/v1/logout', 'AccountController@Logout');


Route::auth();
Route::get('/home', 'HomeController@index');
