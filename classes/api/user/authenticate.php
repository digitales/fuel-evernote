<?php
namespace Evernote\Api\User;

use Evernote\Api\Abstract_Api;

/**
 * Searching users, getting user information
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Authenticate extends Abstract_Api
{
    protected static $user_store;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        static::$user_store = $this->client->get_user_client();
    }
    
    
    public function authenticate($username, $password, $consumerKey, $consumerSecret)
    {
        
        return static::$user_store->authenticate($username, $password, $consumerKey, $consumerSecret);
    }
    
    
    public function long_session( $username, $password, $consumerKey, $consumerSecret, $deviceIdentifier, $deviceDescription )
    {
        return static::$user_store->authenticateLongSession($username, $password, $consumerKey, $consumerSecret, $deviceIdentifier, $deviceDescription);
    }
    
    
    public function to_business( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        
        return static::$user_store->authenticateToBusiness( $access_key );
    }
    
    
    public function refresh( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        
        return static::$user_store->refreshAuthentication( $access_key );
    }

}