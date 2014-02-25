<?php

use League\OAuth2\Client\Provider\Github;
class LoginController extends BaseController {

//Process the login
	public static function GitHubLogin()
	{
            //return View::make('login');
		$provider = new League\OAuth2\Client\Provider\Github(array(
			//Won't want to actually post this to GitHub, will be put in a config file for the app
			'clientId' => 'fd0b49991778467ebe9d',
			'clientSecret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
			'redirectUri' => 'http://54.200.185.101/login'
		));
		$organization = "EnglewoodCodes";
		
		if(!isset($_GET['code']))
		{
			//If we do not have an authorization code, then get one
			$provider->authorize();
		}
		else
		{
			try
			{
				//Try to get an access token (using the authorization code grant)
				$t = $provider->getAccessToken('authorization_code', array('code' => $_GET['code']));
				try
				{
					echo "<script type='text/javascript'>alert('Successful Login!');</script>";
					//If we get an access token, now attempt to get the user's details
					$userDetails = $provider->getUserDetails($t);
					$userName = $provider->getUserUid($t);
					
					//Are they a user in our user table?
					$userExists = laraveldb::table('users')->where('username',$userName)->find();
					
					
					/*
					if($userExists) //In Table
					{
                                            //Check The specified group on GitHub to see if they are in it. If not, delete from table.
                                            //IF they are, continue login
					    $user = laraveldb::table('users')->where('username', $userName)->get();
					    $tableId = $user->$user_id;
					    
					    if()//In Table, Member of Group
						{
							//They are logged in. Put Userid and tableId into session.
							Session::put('uid', 'userName');
							Session::put('tableId', 'tableId');
						}
						else//In Table, Not a Member of Group
						{
							//Delete them from the table
							DB::table('users')->where('user_id', $tableId)->delete();
							//Go back to initial login page.
							return Redirect::to('login');
						}
					    
					    //They are logged in. Store cookie with userID matching their entry.
					}
					else //Not in Table 
					{
						//Check to see if they are a member of the specified group on GitHub
						if()//Not in Table, Member of Group
						{
							//Make new entry in the login table
							$id = DB::table('users')->insertGetId(
							    array('username' => $userName, 'oauth' => $t)
							);
							$user = laraveldb::table('users')->where('username', $userName)->get();
							$tableId = $user->$user_id;
							//They are logged in. Put Userid and tableId into session.
							Session::put('uid', 'userName');
							Session::put('tableId', 'tableId');
						}
						else//Not in Table, Not a Member of Group
						{
							//Go back to initial login page.
							return Redirect::to('login');
						}
					}
					*/
					
				} catch (Exception $e)
				{
				    //We failed to get the user details. Go back to initial login page.
				    echo "<script type='text/javascript'>alert('Failed to get user details');</script>";
				    return Redirect::to('login');
				    
				}
			} catch (Exception $e)
			{
			    //We failed to get the access token. Go back to initial login page.
			    echo "<script type='text/javascript'>alert('Failed to get Access Token');</script>";
			    return Redirect::to('login');
			    
			}
		} //End else
	} //End function GitHubLogin
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