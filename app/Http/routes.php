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

Route::get('/', [
	'as' => 'main.root',
	'uses' => 'MainController@index',
]);

// Authentication routes...
Route::get('auth/login', [
	'as' => 'auth.login',
	'uses' => 'Auth\AuthController@getLogin',
]);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', [
	'as' => 'auth.logout',
	'uses' => 'Auth\AuthController@getLogout',
]);

Route::get('admin', [
	'as' => 'admin.home',
	'uses' => 'AdminController@home',
]);
Route::any('admin/admins', [
	'as' => 'admin.admins',
	'uses' => 'AdminController@admins',
]);
Route::get('admin/translators', [
	'as' => 'admin.translators',
	'uses' => 'AdminController@translators',
]);