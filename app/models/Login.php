<?php

class Login
{
    
    private $provider;
    
    private $token;
    
    private $userDetails;
    
    private $userName;
    
    private $tableId;
    
    function __construct()
    {
	$this->provider = $this->getProvider();
        
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
                    $this->userDetails = getDetails();
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
    
    /**
    *
    * Begins the Laravel Session, Storing userName, userId, and token within the session
    *
    */
    public function beginSession()
    {
	Session::put('uid',$this->userName);
	//Session::put('tableId', '$tableId');
	Session::put('token', $this->token);
    }
}