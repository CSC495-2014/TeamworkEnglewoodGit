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

//Route::get('login', function()
//{
//	return View::make('login');
//});

Route::post('login', 'LoginController@gitHubLoginPost');
Route::get('login', 'LoginController@gitHubLoginPost');

Route::get('user/{user}/project/{project}/editor', function($user, $project)
{
	return View::make('editor', ['user' => $user, 'project' => $project]);
});

Route::get('user/{user}/projects', 'ProjectsController@display');

Route::get('/user/{user}/project/{project}/is-cloned', 'GitController@isCloned');

Route::pattern('file', '.*');
Route::resource('user.project.file', 'FileController');
Route::post('user/{user}/project/{project}/mkdir', 'FileController@mkdirPost');
Route::post('user/{user}/project/{project}/files', 'FileController@indexPost');
Route::post('user/{user}/project/{project}/move', 'FileController@movePost');
Route::post('user/{user}/project/{project}/copy', 'FileController@copyPost');

Route::post('/user/{user}/project/{project}/git-cmd', 'GitController@cmd');

Route::get('/user/{user}/project/{project}/git-status', 'GitController@gitStatus');

/*
Branch Operations:Commit Operations
*/
Route::post('/user/{user}/project/{project}/git-add', 'GitController@gitAdd');

Route::delete('/user/{user}/project/{project}/git-rm', 'GitController@gitRm');

Route::post('/user/{user}/project/{project}/git-commit', 'GitController@commit');

/*
Branch Operations:Push
*/
Route::post('/user/{user}/project/{project}/git-push', 'GitController@push');

/*
Branch Operations:Pull Changes
*/
Route::post('/user/{user}/project/{project}/git-pull', 'GitController@pull');

/*
Repository Operations:Edit
*/
Route::post('/user/{user}/project/{project}/git-remote', 'GitController@addRemote');

Route::delete('/user/{user}/project/{project}/git-remote', 'GitController@removeRemote');

/*
Repository Operations:Clone
*/
Route::post('/user/{user}/project/{project}/git-clone', 'GitController@gitClone');

/*
Custom Command Handling
*/
Route::post('/user/{user}/project/{project}/git', 'GitController@customCmd');