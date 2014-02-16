<?php
//<?php

class LoginController extends BaseController {

//Process the login
	public function GitHubLogin()
	{
            return View::make('login');
		$provider = new League\OAuth2\Client\Provider\Github(array(
			//Won't want to actually post this to GitHub, will be put in a gitignore file
			'clientId' => 'fd0b49991778467ebe9d',
			'clientSecret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
			'redirectUri' => 'http://54.200.185.101/login'
		));
		
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
					//If we get an access token, now attempt to get the user's details
					$userDetails = $provider->getUserDetails($t);
					
					foreach($userDetails as $attribute => $value)
					{
						echo "<script type='text/javascript'>alert('Successful Login!');</script>";
						//Going to want to store these in an easy to access form, not just print them out
						//var_dump($attribute, $value) . PHP_EOL . PHP_EOL;
					}
					
					//Are they a user in our user table?
					//Rachel and Casey will provide command to check
					//if(/*Present in table*/)
					//{
                                            //Check The specified group on GitHub to see if they are in it. If not, delete from table.
                                            //IF they are, continue login
					    //They are logged in. Store cookie with userID matching their entry.
					//}
					//else //They do not yet exist in the user table. 
					//{
						//Check to see if they are a member of the specified group on GitHub
						//GitHub API lists this command to check if a user is publicly or privately a member
						//of an organization:
						//GET /orgs/:org/members/:user
						//We could also compare the user to the list of contributors to a certain repo:
						//GET /repos/:owner/:repo/contributors
						//Likely we will use the organization method, as the students would be a member of an
						//overall group, but to my knowledge they don't have one central repository
						//if(/*Member of group*/)
						//{
							//Make new entry in the login table
							//Rachel and Casey will provide command to add
							//They are logged in. Store cookie with user id matching their entry.
						//}
						//else//Not a member
						//{
							//Go back to initial login page.
						//}
					//}
					
				} catch (Exception $e)
				{
				    echo "<script type='text/javascript'>alert('Failed to get user details');</script>";
				    //We failed to get the user details. Go back to initial login page.
				}
			} catch (Exception $e)
			{
			    echo "<script type='text/javascript'>alert('Failed to get Access Token');</script>";
			    //We failed to get the access token. Go back to initial login page.
			}
		} //End else
	} //End function GitHubLogin
}