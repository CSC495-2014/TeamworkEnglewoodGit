<?php

class LoginController extends BaseController {

//Process the login
    public static function GitHubLogin()
    {
	$gitHubLogin = new Login();
	$gitHubLogin->beginSession();
	$gitHubLogin->testSession();
    }
}