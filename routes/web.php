<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix'=>'admincontrol', 'middleware'=>'admin'], function(){

	Route::get('/', 'AdminController@index');

	Route::group(['prefix'=>'access'], function(){

		Route::get('/', 'AccessController@index');

		Route::get('/methods', 'AccessController@getMethods');

		Route::get('/user', 'AccessController@getMethodUser');


		Route::post('/add', 'AccessController@addMethod');

		Route::post('/remove', 'AccessController@removeMethod');

	});

	Route::group(['prefix'=>'user'], function(){

		Route::get('/', 'UserController@index');

		Route::get('/data', 'UserController@data');

		Route::post('/create', 'UserController@create');

		Route::post('/update', 'UserController@update');

		Route::post('/remove', 'UserController@remove');

	});

});