<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api;
/*
 *public function listTags($authenticationToken);
  public function listTagsByNotebook($authenticationToken, $notebookGuid);
  public function getTag($authenticationToken, $guid);
  public function createTag($authenticationToken, $tag);
  public function updateTag($authenticationToken, $tag);
  public function untagAll($authenticationToken, $guid);
  public function expungeTag($authenticationToken, $guid);
  */
/**
 * Getting information regarding the tags
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Tag extends Abstract_Api
{
    protected static $note_store, $tag;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$note_store = $this->client->get_note_client();
    }
    
    
    /**
     * Forge / create a new tag
     *
     * @param array $tag || null
     *
     * @return object EDAM\Types\Tag
     */
    public function forge( $tag = null )
    {
        static::$tag = new \EDAM\Types\Tag( $tag );
        return static::$tag;
    }
    
    
    public function get_list( )
    {
        return self::$note_store->listTags( $this->client->get_access_key() );
    }
    
    public function get_list_by_notebook( $notebook_guid )
    {
        return self::$note_store->listTagsByNotebook( $this->client->get_access_key(), $notebook_guid );
    }
    
    public function get( $guid )
    {
        return self::$note_store->getTag( $this->client->get_access_key(), $guid );
    }
    
    public function create( $tag )
    {
        return self::$note_store->createTag( $this->client->get_access_key(), $tag );
    }
    
    public function update( $tag )
    {
        return self::$note_store->updateTag( $this->client->get_access_key(), $tag );
    }
    
    public function untag_all( $guid )
    {
        return self::$note_store->untagAll( $this->client->get_access_key(), $guid );
    }
    
    public function expunge( $guid )
    {
        return self::$note_store->expungeTag( $this->client->get_access_key(), $guid );
    }

}