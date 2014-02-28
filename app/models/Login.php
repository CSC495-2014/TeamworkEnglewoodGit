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
        echo "<script type='text/javascript'>alert('Testing Config File');</script>";
        //Config::get('login.variable_name');
	$this->provider = new OAuth2\Client\Provider\Github(array(
	    'clientId' => Config::get(login.clientId),
	    'clientSecret' => Config::get(login.clientSecret),
	    'redirectUri' => Config::get(login.redirectUri),
	    'scopes' => Config::get(login.scopes)
	));
        if(!isset($_GET['code']))
	{
	    $this->provider->authorize();
	}
        else
        {
            try
            {
                $this->token = $this->provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
                try
                {
                    $this->userDetails = $this->provider->getUserDetails($this->token);
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
    
    public function beginSession()
    {
        echo "<script type='text/javascript'>alert('Beginning Session');</script>";
        echo "<script type='text/javascript'>alert('Testing beginSession: $this->userName');</script>";
	Session::put('uid',$this->userName);
	//Session::put('tableId', '$tableId');
	Session::put('token', $this->token);
    }
    
    public function testSession()
    {
        $user = Session::get('uid');
        //$id = Session::get('tableId');
        $t = Session::get('token');
        echo "<script type='text/javascript'>alert('Testing Session:');</script>";
        echo "<script type='text/javascript'>alert('$user');</script>";
    }
    
}