<?php

class Login
{
    private $provider;
    
    private $token;
    
    private $userDetails;
    
    private $userName;
    
    private $tableId;
    
    private $organization;
    
    /**
    *
    * Redirect to GitHub for authorization, then populate $token, $userDetails,
    * $userName and $organization
    *
    */
    function __construct()
    {
		echo "<script type='text/javascript'>alert('FWEIOJFWIOFWE');</script>";
		$this->provider = $this->_getProvider();
			$this->organization = Config::get('oauth.organization');
			if(!isset($_GET['code']))
		{
			$this->provider->authorize();
		}
			else
			{
				try
				{
					$this->token = $this->_getToken();
					try
					{
						$this->userDetails = $this->_getDetails();
						$this->userName = $this->userDetails->nickname;
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
    }
    
    /**
    *
    * Create a new GitHub Provider
    *
    *@return Github $provider
    */
    private function _getProvider()
    {
        return new OAuth2\Client\Provider\Github(array(
	    'clientId' => Config::get('oauth.clientId'),
	    'clientSecret' => Config::get('oauth.clientSecret'),
	    'redirectUri' => Config::get('oauth.redirectUri'),
	    'scopes' => Config::get('oauth.scopes')
		));
    }
    
    /**
    *
    * Using the user authorization, make a request to GitHub to provide access token
    *
    *@return AccessToken $token
    */
    private function _getToken()
    {
        return $this->provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
    }
    
    /**
    *
    * Using the token, make a request to GitHub to provide array of user details
    *
    *@return array $userDetails
    */
    private function _getDetails()
    {
        return $this->provider->getUserDetails($this->token);
    }
    
    /**
    *
    * Once the userDetails are obtained, check if they are present in our user table, then check if
    * they are a member of the specified group
    * 
    */
    public function processUser()
    {
		//$userExists = $this->userExists();
		$userInGroup = $this->_checkUserGroup();
		
		if($userInGroup)
		{
			echo "<script type='text/javascript'>alert('In Group');</script>";
			echo "<script type='text/javascript'>alert('Login for $this->userName');</script>";
			/*
			if(!is_null($userExists))
			{
			$this->tableId = $userExists
			echo "<script type='text/javascript'>alert('In Group, In Table');</script>";
			}
			else
			{
			echo "<script type='text/javascript'>alert('In Group, Not In Table');</script>";
			//Database Query to Add User Goes Here
			}
			$this->beginSession();
			return array (, $this->userName, $this->tableId, $this->token);
			*/
		}
		else
		{
			echo "<script type='text/javascript'>alert('Not In Group');</script>";
			/*
			if(!is_null($userExists))
			{
			//Database Query to Delete User Goes Here
			echo "<script type='text/javascript'>alert('Login Failed: Not a member of group. User deleted');</script>";
			//Route to Login Page
			}
			else
			{
			echo "<script type='text/javascript'>alert('Login Failed: Not a member of group');</script>";
			//Route to Login Page
			}
			*/
		}
    }
    
    /**
    *
    * Grab a list of the organizations a member belongs to, and check them against the organization specified
    * In the configuration file. Return true if they are a member
    *
    *@return bool $userInGroup
    */
    private function _checkUserGroup()
    {
		// create curl resource 
        $ch = curl_init(); 

        // set url
		
		//https://api.github.com/users/$this->userName/orgs?access_token=$this->token
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/orgs/$this->organization/members/$this->userName?access_token=$this->token");

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_USERAGENT, "TeamworkEnglewoodGit");
	
		if(curl_exec($ch) === false)
		{
			echo 'Curl error: ' . curl_error($ch);
		}
		else
		{
			// $output contains the output string 
			$output = curl_exec($ch);
		
			// close curl resource to free up system resources 
			curl_close($ch);
	
			$resultsArray = json_decode($output, true);
			
			foreach ($resultsArray as $innerArray) {
			//  Check type
				if (is_array($innerArray)){
				//  Scan through inner loop
					foreach ($innerArray as $value) {
						echo $value;
					}
				}else{
				// one, two, three
					echo $innerArray;
				}	
			}
			
			/*
			for ($x=0; $x<count($resultsArray); $x++)
			{
				if (in_array($this->organization, $resultsArray{$x})) {
					return true;
				}
			}
			return true;
			*/
		}
		return false;
		
		/*Errors with Requests Library, leave out for now
		//$request = Reque\st::header('User-Agent');
		$url = 'https://api.github.com/users/userName/orgs?access_token=userToken';
		$headers = array('User-Agent' => 'TeamEnglewoodGit');
		//'Content-Type' => 'application/json'
		$data = array('userName' => $this->userName, 'userToken' => $this->token);
		$request = Requests::get($url, $headers, $data);
		//'https://api.github.com/users/$this->userName/orgs?access_token=$this->token'
		
		//$resultsArray=json_decode($request->body);
		var_dump($request->body);
		
		echo "<script type='text/javascript'>alert('Successful HTTP request');</script>";
		
		//for ($x=0; $x<count($resultsArray); $x++)
		//{
			//if (in_array($this->organization, $resultsArray{0})) {
			//return true;
			//}
		//}
		return false;
	    */
    }
    
    public function getUserName()
    {
		return $this->userName;
    }
    
    public function getTableId()
    {
		return $this->tableId;
    }
    
    public function getToken()
    {
		return $this->token;
    }
}