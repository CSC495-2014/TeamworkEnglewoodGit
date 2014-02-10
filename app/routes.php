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

Route::get('user/{user}/project/{project}/editor', function($user, $project)
{
    return View::make('editor', ['user' => $user, 'project' => $project]);
});

Route::pattern('file', '.*');
Route::resource('user.project.file', 'FileController');
Route::post('user/{user}/project/{project}/files', 'FileController@indexPost');

/*
Branch Operations:Commit Operations
*/
Route::post('/user/{user}/project/{project}/git-add', GitController@stageFiles);

Route::post('/user/{user}/project/{project}/git-commit', GitController@commit);

/*
Branch Operations:Push
*/
Route::post('/user/{user}/project/{project}/git-push', GitController@push);

/*
Branch Operations:Merge
*/
Route::post('/user/{user}/project/{project}/git-merge', GitController@merge);

/*
Branch Operations:Pull Changes
*/
Route::post('/user/{user}/project/{project}/git-pull', GitController@pull);

/*
Branch Operation:Retrieve List
*/
Route::get('/user/{user}/project/{project}/git-branch', GitController@getBranch);

/*
Branch Operations:Create New
*/
Route::post('/user/{user}/project/{project}/git-branch', GitController@createBranch);

/*
Branch Operations:Delete
*/
Route::delete('/user/{user}/project/{project}/git-branch', GitController@deleteBranch);

/*
Branch Operations:Checkout
*/
Route::post('/user/{user}/project/{project}/git-checkout', GitController@checkoutBranch);

/*
Branch Operations:Retrieve Past Commits (Current Branch)
*/
Route::get('/user/{user}/project/{project}/git-log', GitController@listBranchCommits);

/*
Repository Operations
Repository Operations:Download Changes
*/
Route::post('/user/{user}/project/{project}/git-fetch', GitController@downloadChanges);

/*
Repository Operations:List Remote Repositories
*/
Route::get('/user/{user}/project/{project}/git-remote', GitController@listRemoteRepos);

/*
Repository Operations:Edit
*/
Route::post('/user/{user}/project/{project}/git-remote', GitController@createNewRemoteRepo);

Route::delete('/user/{user}/project/{project}/git-remote', GitController@deleteRemoteRepo);

/*
Custom Command Handling
*/
Route::post('/user/{user}/project/{project}/git', GitController@customCmd);
