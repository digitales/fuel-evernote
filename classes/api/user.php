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

    
    public function get( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        
        return self::$user_store->getUser( $access_key );
    }
    
    public function get_public_info( $username )
    {        
        return self::$user_store->getPublicUserInfo($username);
    }
    
    
    public function get_premium_info( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        return self::$user_store->getPremiumInfo( $access_key );
    }
    
    
    public function get_note_store_url( $access_key = null )
    {
        if ( ! $access_key ){
            $access_key = $this->client->get_access_key();
        }
        return self::$user_store->getNoteStoreUrl( $access_key );
    }

}