<?php
//include(app_path().'/models/DatabaseQueries.php');
class Login
{
    private $provider;
    
    private $token;
    
    private $userDetails;
    
    private $userName;
    
    private $tableId;
    
    private $organization;
	
	private $email;
    
    /**
    *
    * Redirect to GitHub for authorization, then populate $token, $userDetails,
    * $userName and $organization
    *
    */
    function __construct()
    {
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
						$this->email = $this->userDetails->email;
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
    * @return bool $validUser
    */
    public function processUser()
    {
		//$databaseObj = new DatabaseQueries();
		$this->tableId = DatabaseQueries::GetUserId($this->userName);
		$userInGroup = $this->_checkUserGroup();
		
		if($userInGroup)
		{
			if(!is_null($this->tableId))
			{
				//echo "<script type='text/javascript'>alert('In Group, In Table');</script>";
			}
			else
			{
				//echo "<script type='text/javascript'>alert('In Group, Not In Table');</script>";
				$this->publicKeyPost();
				DatabaseQueries::InsertUser($this->userName, $this->email);
				//echo "<script type='text/javascript'>alert('Added User');</script>";
			}
			return true;
		}
		else
		{
			//echo "<script type='text/javascript'>alert('Not In Group');</script>";
			
			if(!is_null($this->tableId))
			{
				echo "<script type='text/javascript'>alert('Login Failed: Not a member of group. User deleted');</script>";
				DatabaseQueries::DeleteUser($this->userName);
			}
			else
			{
				echo "<script type='text/javascript'>alert('Login Failed: Not a member of group');</script>";
			}
			
			return false;
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
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => "token $this->token",
			'User-Agent' => 'TeamworkEnglewoodGit'
		];
		$request = Requests::get("https://api.github.com/users/$this->userName/orgs", $headers, []);
		$resultsArray = json_decode($request->body, true);
			foreach ($resultsArray as $orgArray) {
			//Make sure the request passed back an array of array's (check that the inside object is an array)
				if (is_array($orgArray)){
					if(in_array($this->organization, $orgArray))
					{
						return true;
					}
				}else{
					echo "<script type='text/javascript'>alert('Organization Check Failed: Not an Array');</script>";
				}	
			}
    }
	
	/**
    *
    * Call for the generation of an RSA key pair, and post the public key to the user account under the name TeamworkEnglewoodGit
    *
    */
	public function publicKeyPost()
	{
		$publicKey = FileSystem::sshKeyGen($this->userName);
		
		$headers = [
			'Accept' => 'application/json',
			'Authorization' => "token $this->token",
			'User-Agent' => 'TeamworkEnglewoodGit'
		];
		
		$data = array(
			'title' => "TeamworkEnglewoodGit",
			'key' => $publicKey
		);
		
		$jsonData = json_encode($data);
		
		$request = Requests::post("https://api.github.com/user/keys", $headers, $jsonData);
		
		$resultsArray = json_decode($request->body, true);
		var_dump($resultsArray);
	}
    
	/**
    *
    * Return the user name of the current user
    *
    *@return string $userName
    */
    public function getUserName()
    {
		return $this->userName;
    }
    
	/**
    *
    * Return the table Id of the current user
    *
    *@return int $tableId
    */
    public function getTableId()
    {
		return $this->tableId;
    }
    
	/**
    *
    * Return the user token of the current user
    *
    *@return string $token
    */
    public function getToken()
    {
		return $this->token;
    }
	
	/**
    *
    * Return the email of the current user
    *
    *@return string $email
    */
	public function getEmail()
	{
		return $this->email;
	}
}