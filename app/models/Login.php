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
	$this->provider = $this->getProvider();
        $this->organization = Config::get('oauth.organization');
        if(!isset($_GET['code']))
	{
	    $this->provider->authorize();
	}
        else
        {
            try
            {
                $this->token = $this->getToken();
                try
                {
                    $this->userDetails = $this->getDetails();
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
    private function getProvider()
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
    private function getToken()
    {
        return $this->provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
    }
    
    /**
    *
    * Using the token, make a request to GitHub to provide array of user details
    *
    *@return array $userDetails
    */
    private function getDetails()
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
	$userInGroup = $this->checkUserGroup();
	
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
    private function checkUserGroup()
    {
	// create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/$this->userName/orgs?access_token=$this->token");

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
	    
	    for ($x=0; $x<count($resultsArray); $x++)
	    {
		if (in_array($this->organization, $resultsArray{$x})) {
		    return true;
		}
	    } 
	}
	return false;
    }
    
    /**
    *
    * Begins the Laravel Session, Storing userName, userId, and token within the session, then
    * routes to the projects page
    *
    */
    public function beginSession()
    {
        echo "<script type='text/javascript'>alert('Beggining Session');</script>";
	Session::put('uid',$this->userName);
	Session::put('tableId', $this->tableId);
	Session::put('token', $this->token);
	//Route to Projects Page
    }
}