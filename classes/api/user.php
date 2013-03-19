<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api,
    Evernote\Api\User\Authenticate;

/**
 * Searching users, getting user information
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class User extends Abstract_Api
{
    protected static $user_store;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$user_store = $this->client->get_user_client();
    }
    
    
    
    public function authenticate()
    {
        return new Authenticate( $this->client );        
    }
    
    
    /**
     * Returns the User corresponding to the provided authentication token, or throws an exception if this token is not valid. The level of detail provided in the returned User structure depends on the access level granted by the token, so a web service client may receive fewer fields than an integrated desktop client.
     *
     * @param string $access_key
     *
     * @return object User
     */
    public function get( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        
        return self::$user_store->getUser( $access_key );
    }
    
    /**
     * Asks the UserStore about the publicly available location information for a particular username.
     *
     * @param string $username required
     *
     * @return object User
     */
    public function get_public_info( $username )
    {        
        return self::$user_store->getPublicUserInfo($username);
    }
    
    
    /**
     * Returns information regarding a user's Premium account corresponding to
     * the provided authentication token, or throws an exception if this token is not valid.
     *
     * @param string $access_key the authentication token for the user
     *
     * @return
     */
    public function get_premium_info( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        return self::$user_store->getPremiumInfo( $access_key );
    }
    
    
    /**
     * Returns the URL that should be used to talk to the NoteStore for the
     * account represented by the provided authenticationToken.
     *
     * This method isn't needed by most clients, who can retrieve the correct
     * NoteStore URL from the AuthenticationResult returned from the authenticate
     * or refreshAuthentication calls. This method is typically only needed to
     * look up the correct URL for a long-lived session token (e.g. for an OAuth web service).
     *
     * @param string $access_key authentication token for the user
     *
     * @return
     */
    public function get_note_store_url( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        return self::$user_store->getNoteStoreUrl( $access_key );
    }

}