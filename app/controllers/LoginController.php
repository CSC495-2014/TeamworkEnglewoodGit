<?php

class LoginController extends BaseController
{
//Process the login
    public static function GitHubLogin()
    {
	$provider = new OAuth2\Client\Provider\Github(array(
	    'clientId' => 'fd0b49991778467ebe9d',
	    'clientSecret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
	    'redirectUri' => 'http://54.200.185.101/login',
	    'scopes' => array('user','repo', 'admin:public_key', 'read:org')
	));
	
	if(!isset($_GET['code']))
	{
	    $provider->authorize();
	}
	else
	{
	    try
	    {
		getToken($provider);
	    }
	    catch (Exception $e)
	    {
		echo "<script type='text/javascript'>alert('Failed to get Token');</script>";
	    }
	}
    } //End function GitHubLogin
    
    public function getToken($provider)
    {
	$t = $provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
	try
	{
	    getUser($provider, $t);
	}
	catch (Exception $e)
	{
	    echo "<script type='text/javascript'>alert('Failed to obtain User');</script>";
	}
    }
    
    public function processUser($t, $userDetails)
    {
	$userName = $userDetails->nickname;
	$organization = '';
	$userExists = userExists($userName);
	$userInGroup = checkUserGroup($userName, $organization);
	
	if($userInGroup)
	{
	    if($userExists)
	    {
		echo "<script type='text/javascript'>alert('In Group, In Table');</script>";
		$tableId = getTableId($userName);
	    }
	    else
	    {
		echo "<script type='text/javascript'>alert('In Group, Not In Table');</script>";
		$tableId = addUser($userName);
	    }
	    beginSession($userName, $tableId);
	}
	else
	{
	    if($userExists)
	    {
		$tableId = getTableId($userName);
		deleteUser($tableId);
		echo "<script type='text/javascript'>alert('Login Failed: Not a member of group. User deleted');</script>";
	    }
	    else
	    {
		echo "<script type='text/javascript'>alert('Login Failed: Not a member of group');</script>";
	    }
	}
    }
    
    public function beginSession($userName, $tableId, $t)
    {
	echo "<script type='text/javascript'>alert('Beginning Session');</script>";
	Session::put('uid', 'userName');
	Session::put('tableId', 'tableId');
	Session::put('token', 't');
    }
    
    public function checkUserGroup()
    {
	return true;
    }
    
    public function getUser($provider, $t)
    {
	try
	{
	    $userDetails = $provider->getUserDetails($t);
	    echo "<script type='text/javascript'>alert('Obtained details for User: $userDetails->nickname');</script>";
	    processUser($t, $userDetails);
	}
	catch (Exception $e)
	{
	    echo "<script type='text/javascript'>alert('Failed to obtain user details');</script>";
	}
    }
    
    public function userExists($userName)
    {
	echo "<script type='text/javascript'>alert('Checking Table');</script>";
	$userExists = laraveldb::table('users')->where('username',$userName)->find();
	return $userExists;
    }
    public function deleteUser($tableId)
    {
	echo "<script type='text/javascript'>alert('Deleting User');</script>";
	DB::table('users')->where('user_id', $tableId)->delete();
    }
    
    public function addUser($userName)
    {
	echo "<script type='text/javascript'>alert('Adding User');</script>";
	$id = DB::table('users')->insertGetId(array('username' => $userName, 'oauth' => $t));
	return $id;
    }
    
    public function getTableId($userName)
    {
	echo "<script type='text/javascript'>alert('Getting Table ID');</script>";
	$user = laraveldb::table('users')->where('username', $userName)->get();
	$tableId = $user->$user_id;
	return $tableId;
    }
    
} //End LoginController

//If present
//Add User
//Delete User
//Get TableID
//Edit Token
/*
//Check if Exists
$userExists = laraveldb::table('users')->where('username',$userName)->find();
					
//Delete User
DB::table('users')->where('user_id', $tableId)->delete();
					
//Add User
$id = DB::table('users')->insertGetId(
    array('username' => $userName, 'oauth' => $t)
);
					
//Get Table ID
$user = laraveldb::table('users')->where('username', $userName)->get();
$tableId = $user->$user_id;
*/