<?php

return array(
  
    /*
    |--------------------------------------------------------------------------
    | Online or Offline
    |--------------------------------------------------------------------------
    |
    | This option will allow you to determine whether to Login Online from
    | the server, or offline on a local machine. Due to how Oauth2 works,
    | unless you are running the app directly from the registered server
    | it will not work
    |
    */
  
    'online' => true,
    
    /*
    |--------------------------------------------------------------------------
    | ClientId
    |--------------------------------------------------------------------------
    |
    | Holds the Client ID recieved when application is registered with GitHub
    | for OAuth2 use
    |
    */
    
    'clientId' => 'fd0b49991778467ebe9d',
    
    /*
    |--------------------------------------------------------------------------
    | ClientSecret
    |--------------------------------------------------------------------------
    |
    | Holds the Client Secret recieved when application is registered with
    | GitHub for OAuth2 use
    |
    */
    
    'clientSecret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
    
    /*
    |--------------------------------------------------------------------------
    | Redirect URI
    |--------------------------------------------------------------------------
    |
    | Holds the redirect URI entered when application is registered with GitHub
    | for OAuth2 use
    |
    */
    
    'redirectUri' => 'http://54.200.185.101/login',
    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | Holds the scopes that your app requests access for when generating the
    | user Authorization
    |
    */
    
    'scopes' => array('user','repo', 'admin:public_key', 'read:org'),
    
    /*
    |--------------------------------------------------------------------------
    | Offline UserName
    |--------------------------------------------------------------------------
    |
    | The default userName when logging in in offline mode
    |
    */
    
    'offlineUserName' => 'IamAtest',
    
    /*
    |--------------------------------------------------------------------------
    | Offline Table ID
    |--------------------------------------------------------------------------
    |
    | The default tableID when logging in in offline mode
    |
    */
    
    'offlineTableId' => 42,
    
    /*
    |--------------------------------------------------------------------------
    | Offline Token
    |--------------------------------------------------------------------------
    |
    | The default token when logging in in offline mode (Note: Not a real Oauth2
    | token)
    |
    */
    'offlineToken' => 'jfieowqpjreiwoqjrweqpjgirweq',
    
    /*
    |--------------------------------------------------------------------------
    | Organization Name
    |--------------------------------------------------------------------------
    |
    | The name of the organization to confirm membership with
    |
    */
    'organization' => 'CSC495-2014'
  
);