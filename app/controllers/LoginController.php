<?php

class LoginController extends BaseController {

    /**
    *
    * Drive the Login Process, online or offline depending on config variable
    *
    */
    public function gitHubLoginPost()
    {
		if(Config::get('oauth.online'))
		{
			//Standard Online Session
			$gitHubLogin = new Login();
			$gitHubLogin->providerAuthorize();
		}
		else
		{
			//Offline Testing Session
			$user = Config::get('oauth.offlineUserName');
			$userId = Config::get('oauth.offlineTableId');
			$token = Config::get('oauth.offlineToken');
			
			Session::put('uid', $user);
			Session::put('tableId', $userId);
			Session::put('token', $token);
			
			return Redirect::to(URL::to("/user/$user/projects"));
		}
    }
	
	public function gitHubLoginGet()
	{
		$gitHubLogin = new Login();
		try
		{
			$gitHubLogin->fetchToken();
			try
			{
				$gitHubLogin->getDetails();
				$userInGroup = $this->getUserOrgs($gitHubLogin);
				if($userInGroup)
				{
					$gitHubLogin->processValidUser();
					$this->publicKeyPost($gitHubLogin);
					Session::put('uid',$gitHubLogin->getUserName());
					Session::put('tableId', $gitHubLogin->getTableId());
					Session::put('email', $gitHubLogin->getEmail());
					Session::put('token', $gitHubLogin->getToken());
					return Redirect::to(URL::to("/user/$user/projects"));
				}
				else
				{
					echo "<script type='text/javascript'>alert('Login Failed: Not a Member of Group');</script>";
					$gitHubLogin->processInvalidUser();
					return Redirect::to(URL::to("/"));
				}
			}
			catch(Exception $e)
			{
				echo "<script type='text/javascript'>alert('Failed to get User Details');</script>";
			}
		}
		catch(Exception $e)
		{
			echo "<script type='text/javascript'>alert('Failed to get Access Token');</script>";
		}
	}
	
	public function getUserOrgs($gitHubLogin)
	{
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => "token $gitHubLogin->token",
			'User-Agent' => 'TeamworkEnglewoodGit'
		];
		$request = Requests::get("https://api.github.com/users/$gitHubLogin->userName/orgs", $headers, []);
		$resultsArray = json_decode($request->body, true);
			foreach ($resultsArray as $orgArray)
			{
			//Make sure the request passed back an array of array's (check that the inside object is an array)
				if (is_array($orgArray))
				{
					if(in_array($gitHubLogin->organization, $orgArray))
					{
						return true;
					}
				}
				else
				{
					echo "<script type='text/javascript'>alert('Organization Check Failed: Not an Array');</script>";
				}	
		}
	}
	
	/**
    *
    * Call for the generation of an RSA key pair, and post the public key to the user account under the name TeamworkEnglewoodGit
    *
    */
	public function publicKeyPost($gitHubLogin)
	{
		$publicKey = FileSystem::sshKeyGen($gitHubLogin->userName);
		
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => "token $gitHubLogin->token",
			'User-Agent' => 'TeamworkEnglewoodGit'
		];
		
		$data = array(
			'title' => "TeamworkEnglewoodGit",
			'key' => $publicKey
		);
		
		$jsonData = json_encode($data);
		
		$request = Requests::post("https://api.github.com/user/keys", $headers, $jsonData);
		
		$resultsArray = json_decode($request->body, true);
	}
}