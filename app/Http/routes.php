<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	Route::auth();
	Route::get('/clients', 'ClientController@index');
	Route::post('/clients', 'ClientController@store');
	Route::post('client/{id}', 'ClientController@show');
	Route::get('clients/autocomplete', 'ClientController@autocomplete');
    Route::get('/report', 'ReportController@index');
    Route::post('/reports', 'ReportPdfController@index');
    Route::get('/import', 'ImportController@index');
    Route::post('/import', 'ImportController@import');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/inicio', 'HomeController@index');
});
