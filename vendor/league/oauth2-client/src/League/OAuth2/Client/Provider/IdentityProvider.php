<?php

namespace League\OAuth2\Client\Provider;

use Guzzle\Service\Client as GuzzleClient;
use League\OAuth2\Client\Token\AccessToken as AccessToken;
use League\OAuth2\Client\Token\Authorize as AuthorizeToken;
use League\OAuth2\Client\Exception\IDPException as IDPException;

abstract class IdentityProvider
{
    public $clientId = '';

    public $clientSecret = '';

    public $redirectUri = '';

    public $name;

    public $uidKey = 'uid';

    public $scopes = array();

    public $method = 'post';

    public $scopeSeparator = ',';

    public $responseType = 'json';

    protected $cachedUserDetailsResponse;

    public $headers = null;

    public function __construct($options = array())
    {
        foreach ($options as $option => $value) {
            if (isset($this->{$option})) {
                $this->{$option} = $value;
            }
        }
    }

    abstract public function urlAuthorize();

    abstract public function urlAccessToken();

    abstract public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token);

    abstract public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token);

    public function getScopes()
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
    }

    public function getAuthorizationUrl($options = array())
    {
        $state = md5(uniqid(rand(), true));
        setcookie($this->name.'_authorize_state', $state);

        $params = array(
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'scope' => is_array($this->scopes) ? implode($this->scopeSeparator, $this->scopes) : $this->scopes,
            'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
            'approval_prompt' => 'auto'
        );

        return $this->urlAuthorize().'?'.http_build_query($params,'','&',PHP_QUERY_RFC1738);
    }

    public function authorize($options = array())
    {
        header('Location: ' . $this->getAuthorizationUrl($options));
        exit;
    }

    public function getAccessToken($grant = 'authorization_code', $params = array())
    {
        echo "<script type='text/javascript'>alert('start getAccessToken');</script>";
        if (is_string($grant)) {
            echo "<script type='text/javascript'>alert('is_string grant');</script>";
            $grant = 'League\\OAuth2\\Client\\Grant\\'.ucfirst(str_replace('_', '', $grant));
            echo "<script type='text/javascript'>alert('new grant');</script>";
            if ( ! class_exists($grant)) {
                echo "<script type='text/javascript'>alert('class does not exist');</script>";
                throw new \InvalidArgumentException('Unknown grant "'.$grant.'"');
            }
            $grant = new $grant;
            echo "<script type='text/javascript'>alert('grant = new grant');</script>";
        } elseif ( ! $grant instanceof Grant\GrantInterface) {
            echo "<script type='text/javascript'>alert('not instance of');</script>";
            throw new \InvalidArgumentException($grant.' is not an instance of League\OAuth2\Client\Grant\GrantInterface');
        }
        echo "<script type='text/javascript'>alert('Create Default Parameters');</script>";
        $defaultParams = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => $grant,
        );
        echo "<script type='text/javascript'>alert('end default parameters');</script>";
        $requestParams = $grant->prepRequestParams($defaultParams, $params);
        echo "<script type='text/javascript'>alert('Params -> grant');</script>";
        try {
            echo "<script type='text/javascript'>alert('try method');</script>";
            switch ($this->method) {
                case 'get':
                    echo "<script type='text/javascript'>alert('get');</script>";
                    $client = new GuzzleClient($this->urlAccessToken() . '?' . http_build_query($requestParams,'','&',PHP_QUERY_RFC1738));
                    $request = $client->send();
                    $response = $request->getBody();
                    break;
                case 'post':
                    echo "<script type='text/javascript'>alert('post');</script>";
                    $client = new GuzzleClient($this->urlAccessToken());
                    $request = $client->post(null, null, $requestParams)->send();
                    $response = $request->getBody();
                    break;
            }
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            echo "<script type='text/javascript'>alert('catch guzzle error');</script>";
            $raw_response = explode("\n", $e->getResponse());
            $response = end($raw_response);
        }
        echo "<script type='text/javascript'>alert('switch response type');</script>";
        switch ($this->responseType) {
            
            case 'json':
                echo "<script type='text/javascript'>alert('json');</script>";
                $result = json_decode($response, true);
                break;
            case 'string':
                echo "<script type='text/javascript'>alert('string');</script>";
                parse_str($response, $result);
                break;
        }

        if (isset($result['error']) && ! empty($result['error'])) {
            echo "<script type='text/javascript'>alert('result error');</script>";
            throw new IDPException($result);
        }
        echo "<script type='text/javascript'>alert('return grant');</script>";
        return $grant->handleResponse($result);
    
    }

    public function getUserDetails(AccessToken $token, $force = false)
    {
        $response = $this->fetchUserDetails($token);

        return $this->userDetails(json_decode($response), $token);
    }

    public function getUserUid(AccessToken $token, $force = false)
    {
        $response = $this->fetchUserDetails($token, $force);

        return $this->userUid(json_decode($response), $token);
    }

    public function getUserEmail(AccessToken $token, $force = false)
    {
        $response = $this->fetchUserDetails($token, $force);

        return $this->userEmail(json_decode($response), $token);
    }

    public function getUserScreenName(AccessToken $token, $force = false)
    {
        $response = $this->fetchUserDetails($token, $force);

        return $this->userScreenName(json_decode($response), $token);
    }

    protected function fetchUserDetails(AccessToken $token, $force = false)
    {
        if ( ! $this->cachedUserDetailsResponse || $force == true) {

            $url = $this->urlUserDetails($token);

            try {

                $client = new GuzzleClient($url);

                if ($this->headers) {
                    $client->setDefaultOption('headers', $this->headers);
                }

                $request = $client->get()->send();
                $response = $request->getBody();
                $this->cachedUserDetailsResponse = $response;

            } catch (\Guzzle\Http\Exception\BadResponseException $e) {

                $raw_response = explode("\n", $e->getResponse());
                throw new IDPException(end($raw_response));

            }
        }

        return $this->cachedUserDetailsResponse;
    }

}
