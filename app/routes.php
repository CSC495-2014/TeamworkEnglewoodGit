<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('login');
});

Route::get('login', function()
{
	return View::make('login');
});

Route::get('editor', function()
{
	return View::make('editor');
});

Route::resource('user.project.file', 'FileController');
Route::post('user/{user}/project/{project}/files', 'FileController@indexPost');
