<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api;
/*
    public function listSearches($authenticationToken);
    public function getSearch($authenticationToken, $guid);
    public function createSearch($authenticationToken, $search);
    public function updateSearch($authenticationToken, $search);
    public function expungeSearch($authenticationToken, $guid);
  */
/**
 * Search functionality with the Evernote API
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Search extends Abstract_Api
{
    protected static $note_store;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$note_store = $this->client->get_note_client();
    }
    
    public function get_list( )
    {
        return self::$note_store->listSearches( $this->client->get_access_key() );
    }
    
    
    public function get( $guid )
    {
        return self::$note_store->getSearch( $this->client->get_access_key(), $guid );
    }
    
    public function create( $search )
    {
        return self::$note_store->createSearch( $this->client->get_access_key(), $search );
    }
    
    public function update( $search )
    {
        return self::$note_store->updateSearch( $this->client->get_access_key(), $search );
    }
        
    public function expunge( $guid )
    {
        return self::$note_store->expungeSearch( $this->client->get_access_key(), $guid );
    }

}