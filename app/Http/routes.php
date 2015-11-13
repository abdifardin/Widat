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
Route::any('account/{user_id}/edit', [
	'as' => 'main.edit_account',
	'uses' => 'MainController@editAccount',
]);
Route::any('peek', [
	'as' => 'main.peek',
	'uses' => 'MainController@peek',
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

// Admin routes
Route::get('admin', [
	'as' => 'admin.home',
	'uses' => 'AdminController@home',
]);
Route::any('admin/admins', [
	'as' => 'admin.admins',
	'uses' => 'AdminController@admins',
]);
Route::any('admin/translators', [
	'as' => 'admin.translators',
	'uses' => 'AdminController@translators',
]);
Route::any('admin/inspection/{user_id?}', [
	'as' => 'admin.inspection',
	'uses' => 'AdminController@inspection',
]);

// Translator routes

Route::get('translator', [
	'as' => 'translator.home',
	'uses' => 'TranslatorController@home',
]);
Route::get('translator/{user_id}/stats', [
	'as' => 'translator.stats',
	'uses' => 'TranslatorController@stats',
]);
Route::get('translator/{user_id}/score-history', [
	'as' => 'translator.score_history',
	'uses' => 'TranslatorController@scoreHistory',
]);
Route::any('topics', [
	'as' => 'translator.topics',
	'uses' => 'TranslatorController@topics',
]);
Route::any('translate/{topic_id}', [
	'as' => 'translator.translate',
	'uses' => 'TranslatorController@translate',
]);
Route::get('nocando/{topic_id}', [
	'as' => 'translator.nocando',
	'uses' => 'TranslatorController@nocando',
]);

