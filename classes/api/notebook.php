<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api;
/*
  public function listNotebooks($authenticationToken);
  public function getNotebook($authenticationToken, $guid);
  public function getDefaultNotebook($authenticationToken);
  public function createNotebook($authenticationToken, $notebook);
  public function updateNotebook($authenticationToken, $notebook);
  public function expungeNotebook($authenticationToken, $guid);
  */
/**
 * Getting information regarding the notebooks
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Notebook extends Abstract_Api
{
    protected static $note_store, $notebook;

    function __construct( $client = null )
    {
        parent::__construct( $client );

        static::$note_store = $this->client->get_note_client();
    }


    /**
     * Forge / create a new note
     *
     * @param array $notebook
     * @return EDAM\Types\Note
     */
    function forge( $notebook = null )
    {
        static::$notebook = new \EDAM\Types\Notebook( $notebook );

        return static::$notebook;
    }


    public function get_list( )
    {
        return self::$note_store->listNotebooks( $this->client->get_access_key() );
    }

    public function get( $guid )
    {
        return self::$note_store->getNotebook( $this->client->get_access_key(), $guid );
    }

    public function get_default()
    {
        return self::$note_store->getDefaultNotebook( $this->client->get_access_key() );
    }

    public function create( $notebook )
    {
        return self::$note_store->createNotebook( $this->client->get_access_key(), $notebook );
    }

    public function update( $notebook )
    {
        return self::$note_store->updateNotebook( $this->client->get_access_key(), $notebook );
    }

    public function expunge( $guid )
    {
        return self::$note_store->expungeNotebook( $this->client->get_access_key(), $guid );
    }

}