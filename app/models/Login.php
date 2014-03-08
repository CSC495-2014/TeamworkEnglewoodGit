<?php
include(app_path().'/models/DatabaseQueries.php');
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
		echo "<script type='text/javascript'>alert('Starting Login');</script>";
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
					echo "<script type='text/javascript'>alert('Get Token');</script>";
					$this->token = $this->_getToken();
					try
					{
						echo "<script type='text/javascript'>alert('Get Details');</script>";
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
    */
    public function processUser()
    {
		$this->tableId = DatabaseQueries::userExists($this->userName);
		$userInGroup = $this->_checkUserGroup();
		
		if($userInGroup)
		{
			echo "<script type='text/javascript'>alert('In Group');</script>";
			echo "<script type='text/javascript'>alert('Login for $this->userName');</script>";
			
			if(!is_null($this->tableId))
			{
				echo "<script type='text/javascript'>alert('In Group, In Table');</script>";
			}
			else
			{
				echo "<script type='text/javascript'>alert('In Group, Not In Table');</script>";
				DatabaseQueries::insertUsers($this->userName, $this->email);
				echo "<script type='text/javascript'>alert('Added User');</script>";
			}
			
			return true;
		}
		else
		{
			echo "<script type='text/javascript'>alert('Not In Group');</script>";
			
			if(!is_null($this->tableId))
			{
				echo "<script type='text/javascript'>alert('Login Failed: Not a member of group. User deleted');</script>";
				DatabaseQueries::deleteUsers($this->userName);
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
	
	public function getEmail()
	{
		return $this->email;
	}
}