<?php

class Login
{
    
    private $provider;
    
    private $token;
    
    private $userDetails;
    
    private $userName;
    
    private $tableId;
    
    private $organization;
    
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
                    echo "<script type='text/javascript'>alert('Login for $this->userName');</script>";
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
    
    
    public function processUser()
    {
	//$userExists = $this->userExists();
	$userInGroup = $this->checkUserGroup();
	//$this->checkUserGroup();
	var_dump($userInGroup);
	//echo "<script type='text/javascript'>alert('$userInGroup');</script>";
	/*
	if($userInGroup)
	{
	    if($userExists)
	    {
		echo "<script type='text/javascript'>alert('In Group, In Table');</script>";
		$this->tableId = $this->getTableId();
	    }
	    else
	    {
		echo "<script type='text/javascript'>alert('In Group, Not In Table');</script>";
		$this->tableId = $this->addUser();
	    }
	    $this->beginSession();
	}
	else
	{
	    if($userExists)
	    {
		$this->tableId = $this->getTableId();
		$this->deleteUser();
		echo "<script type='text/javascript'>alert('Login Failed: Not a member of group. User deleted');</script>";
	    }
	    else
	    {
		echo "<script type='text/javascript'>alert('Login Failed: Not a member of group');</script>";
	    }
	}
	*/
    }
    
    private function checkUserGroup()
    {
	// create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/orgs/CSC495-2014/members");
	///

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_USERAGENT, "TeamworkEnglewoodGit");

	if(curl_exec($ch) === false)
	{
	    echo 'Curl error: ' . curl_error($ch);
	}
	else
	{
	    echo "<script type='text/javascript'>alert('no error');</script>";
	}
	
        // $output contains the output string 
        $output = curl_exec($ch);
	
	//var_dump($output);

        // close curl resource to free up system resources 
        curl_close($ch);
	
	//var_dump(json_decode($output, true));
	
	$resultsArray = json_decode($output, true);
	
	return $resultsArray{1}->'login';
	
	//echo "<script type='text/javascript'>alert('$found');</script>";
	
    }
    
    /**
    *
    * Begins the Laravel Session, Storing userName, userId, and token within the session
    *
    */
    public function beginSession()
    {
        echo "<script type='text/javascript'>alert('Beggining Session');</script>";
	Session::put('uid',$this->userName);
	//Session::put('tableId', '$tableId');
	Session::put('token', $this->token);
    }
    
    
    public function userExists()
    {
	echo "<script type='text/javascript'>alert('Checking Table');</script>";
	$userExists = DB::table('users')->where('username',$this->userName)->find();
	return $userExists;
        return true;
    }
    public function deleteUser()
    {
	echo "<script type='text/javascript'>alert('Deleting User');</script>";
	DB::table('users')->where('user_id', $this->tableId)->delete();
    }
    
    public function addUser()
    {
	echo "<script type='text/javascript'>alert('Adding User');</script>";
	$id = DB::table('users')->insertGetId(array('username' => $this->userName, 'oauth' => $t));
	return $id;
    }
    
    public function getTableId()
    {
	echo "<script type='text/javascript'>alert('Getting Table ID');</script>";
	$user = DB::table('users')->where('username', $this->userName)->get();
	$tableId = $user->$user_id;
	return $tableId;
    }
    
}