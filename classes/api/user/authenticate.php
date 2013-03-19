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
    
    /**
     * **** Internal only for Evernote apps ****
     * 
     * This is used to check a username and password in order to create a short-lived
     * authentication session that can be used for further actions.
     *
     * This function is only available to Evernote's internal applications.
     * Third party applications must authenticate using OAuth as described at dev.evernote.com.
     *
     * @param  username The username (not numeric user ID) for the account to authenticate against. This function will also accept the user's registered email address in this parameter.
     * @param  password The plaintext password to check against the account. Since this is not protected by the EDAM protocol, this information must be provided over a protected transport (e.g. SSL).
     * @param  consumerKey The "consumer key" portion of the API key issued to the client application by Evernote.
     * @param  consumerSecret The "consumer secret" portion of the API key issued to the client application by Evernote.
     *
     * @return The result of the authentication. If the authentication was successful, the AuthenticationResult.user field will be set with the full information about the User.
     */
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
    
    
    /**
     * **** Internal only for Evernote apps ****
     *
     *  This is used to take an existing authentication token (returned from 'authenticate')
     *  and exchange it for a newer token which will not expire as soon. This must be invoked before the previous token expires.
     *
     *  This function is only availabe to Evernote's internal applications.
     *
     *  From: http://dev.evernote.com/documentation/reference/UserStore.html#Fn_UserStore_refreshAuthentication
     *
     *  @param  authenticationToken The previous authentication token from the authenticate() result.
     *  @return The result of the authentication, with the new token in the result's 'authenticationToken' field. The 'User' field will not be set in the result.
     */
    public function refresh( $access_key = null )
    {        
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        
        return static::$user_store->refreshAuthentication( $access_key );
    }

}