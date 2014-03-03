<?php

class LoginController extends BaseController {

    /**
    *
    * Drive the Login Process
    *
    */
    public static function GitHubLogin()
    {
	if(Config::get('oauth.online'))
	{
	    $gitHubLogin = new Login();
	    $gitHubLogin->processUser();
	}
	else
	{
	    $userName = Config::get('oauth.offlineUserName');
	    $userId = Config::get('oauth.offlineTableId');
	    $token = Config::get('oauth.offlineToken');
	    Session::put('uid', $userName);
	    Session::put('tableId', $userId);
	    Session::put('token', $token);
	    
	    $url = URL::to('user/testing/projects');
	    
	    //return Redirect::to('user/{user}/projects', ['user' => $userName]);
	    //return Redirect::to('user/{user}/projects', [$userName]);
	    //return Redirect::route('user/{user}/projects', $userName);
	    
	    //$hasTable = Schema::hasTable('users');
	    
	    //$results = DB::select('select * from users where $userName = username', array(1));
	    
	    //$results = DB::select('select * from users where user_id = 1', array(1));
	    
	    //$userExists = DB::select('users')->where('username',$userName)->find();
	    //var_dump($results);
	    //echo "<script type='text/javascript'>alert('User Exists: $results[0]');</script>";
	    
	    //Redirect::route('user/$userName/projects');
	}
    }
}