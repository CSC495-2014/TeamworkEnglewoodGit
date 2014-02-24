<?php
//use League\OAuth2\Client\Provider;
use OAuth2\OAuth2;
use OAuth2\Token_Access;
use OAuth2\Exception as OAuth2_Exception;
class NewLoginController extends BaseController{

public static function gitHubLogin($provider)
{
    $provider = OAuth2::provider($provider, array(
        'id' => 'fd0b49991778467ebe9d',
        'secret' => '82c139b5cf2109a8b9ae0670fd0d818640f1b3bc',
    ));

    if ( ! isset($_GET['code']))
    {
        // By sending no options it'll come back here
        return $provider->authorize();
    }
    else
    {
        // Howzit?
        try
        {
            $params = $provider->access($_GET['code']);

                $token = new Token_Access(array(
                    'access_token' => $params->access_token
                ));
                $user = $provider->get_user_info($token);

            // Here you should use this information to A) look for a user B) help a new user sign up with existing data.
            // If you store it all in a cookie and redirect to a registration page this is crazy-simple.
            echo "<pre>";
            var_dump($user);
        }

        catch (OAuth2_Exception $e)
        {
            show_error('That didnt work: '.$e);
        }
    }
}
} //End LoginController
