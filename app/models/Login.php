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
	$this->provider = new OAuth2\Client\Provider\Github(array(
	    'clientId' => 'fd0b49991778467ebe9d',
	    'clientSecret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
	    'redirectUri' => 'http://54.200.185.101/login',
	    'scopes' => array('user','repo', 'admin:public_key', 'read:org')
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