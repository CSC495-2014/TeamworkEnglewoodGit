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
	    $gitHubLogin->beginSession();
	}
	else
	{
	    $userName = Config::get('oauth.offlineUserName');
	    $userId = Config::get('oauth.offlineTableId');
	    $token = Config::get('oauth.offlineToken');
	    Session::put('uid', $userName);
	    Session::put('tableId', $userId);
	    Session::put('token', $token);
	    
	}
    }
}