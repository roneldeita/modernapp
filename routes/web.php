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



Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('/posts', 'HomeController@posts');

Route::post('/create_post', 'HomeController@create_post');

Route::get('/inserted', 'HomeController@inserted');


Route::group(['prefix'=>'admincontrol', 'middleware'=>'admin'], function(){

	Route::get('/', 'AdminController@index');

	Route::group(['prefix'=>'post'], function(){

		Route::get('/', 'PostController@index');

		Route::get('/data', 'PostController@data');

		Route::post('/switchdisplay', 'PostController@switch_display');

		Route::post('deleteposts', 'PostController@delete_posts');

		Route::group(['prefix'=>'category'], function(){

			Route::get('/', 'PostcategoryController@index');

			Route::get('/data', 'PostcategoryController@data');

			Route::post('/create', 'PostcategoryController@create');

			Route::post('/update', 'PostcategoryController@update');

		});

	});

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
	
	Route::group(['prefix'=>'appearance'], function(){

		Route::get('/', 'AppearanceController@index');

		Route::get('/usertheme', 'AppearanceController@user_theme');

		Route::post('changetheme', 'AppearanceController@change_theme');

	});

});