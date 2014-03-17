<?php

class Login
{
    private $provider;
    
    private $token;
    
    private $userDetails;
    
    private $userName;
    
    private $tableId;
    
    private $organization;
	
	private $email;
    
	
	function __contruct()
	{
		$this->provider = new OAuth2\Client\Provider\Github(array(
			'clientId' => Config::get('oauth.clientId'),
			'clientSecret' => Config::get('oauth.clientSecret'),
			'redirectUri' => Config::get('oauth.redirectUri'),
			'scopes' => Config::get('oauth.scopes')
		));;
		$this->organization = Config::get('oauth.organization');
	}
	
	/**
    *
    * Using the user authorization, make a request to GitHub to provide access token
    *
    *@return AccessToken $token
    */
    public function fetchToken()
    {
        return $this->provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
    }
	
	/**
    *
    * Using the token, make a request to GitHub to provide array of user details
    *
    *@return array $userDetails
    */
    public function getDetails()
    {
        $this->userDetails = $this->provider->getUserDetails($this->token);
		$this->userName = $this->userDetails->nickname;
		$this->email = $this->userDetails->email;
    }
 
    public function processValidUser()
    {
		$this->tableId = DatabaseQueries::GetUserId($this->userName);

		if(is_null($this->tableId))
		{
			DatabaseQueries::InsertUser($this->userName, $this->email);
		}	
    }
	
	public function processInvalidUser()
	{
		$this->tableId = DatabaseQueries::GetUserId($this->userName);
			
		if(!is_null($this->tableId))
		{
			DatabaseQueries::DeleteUser($this->userName);
		}
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