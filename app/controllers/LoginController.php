<?php

class LoginController extends BaseController {

    /**
    *
    * Drive the Login Process, online or offline depending on config variable
    *
    */
    public static function GitHubLogin()
    {
		echo "<script type='text/javascript'>alert('JFIEOWJFIEOJFIOEJFIOEWJFIOEWJIOF');</script>";
		if(Config::get('oauth.online'))
		{
			//Standard Online Session
			$gitHubLogin = new Login();
			$validUser = $gitHubLogin->processUser();
			if($validUser)
			{
				//begin session
				echo "<script type='text/javascript'>alert('Beggining Session');</script>";
				$user = $gitHubLogin->getUserName();
				Session::put('uid',$gitHubLogin->getUserName());
				Session::put('tableId', $gitHubLogin->getTableId());
				Session::put('email', $gitHubLogin->getEmail());
				Session::put('token', $gitHubLogin->getToken());
				echo "<script type='text/javascript'>alert('Populated Session');</script>";
				$gitHubLogin->redirectToProjects();
				//Route to Projects Page
				//echo "<script type='text/javascript'>alert('Attempting Route');</script>";
				
				//echo "<script type='text/javascript'>alert('Failed Route');</script>";
				//return Redirect::route('user/{user}/projects', [$userName]);
				//return Redirect::route('user/{user}/projects', $userName);
				//return Redirect::to('/user/$gitHubLogin->getUserName/projects');
			}
			else
			{
				$org = Config::get('oauth.organization');
				echo "<script type='text/javascript'>alert('Login Failed: You are Not a Member of $org on GitHub. Please join $org and try again.');</script>";
			//Stay on login page
			}
		}
		else
		{
			echo "<script type='text/javascript'>alert('Offline Login');</script>";
			//Offline Testing Session
			$userName = Config::get('oauth.offlineUserName');
			$userId = Config::get('oauth.offlineTableId');
			$token = Config::get('oauth.offlineToken');
			
			Session::put('uid', $userName);
			Session::put('tableId', $userId);
			Session::put('token', $token);
			//Route to Projects Page
		}
		//return Redirect::to('login/redirect');
    }
	/*
	public function continueLogin()
	{
		
	}
	*/
	
	/*
	public function redirect()
	{
		$userName = Session::get('uid');
		return Redirect::route('user/{user}/projects','wwforg');
	}
	*/
}