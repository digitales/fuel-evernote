<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api;

/*
 *
 *  public function getSyncState($authenticationToken);
  public function getSyncStateWithMetrics($authenticationToken, $clientMetrics);
  public function getSyncChunk($authenticationToken, $afterUSN, $maxEntries, $fullSyncOnly);
  public function getFilteredSyncChunk($authenticationToken, $afterUSN, $maxEntries, $filter);
  public function getLinkedNotebookSyncState($authenticationToken, $linkedNotebook);
  public function getLinkedNotebookSyncChunk($authenticationToken, $linkedNotebook, $afterUSN, $maxEntries, $fullSyncOnly);
  
*/

/**
 * Getting information regarding the state of the notebook sync
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Sync extends Abstract_Api
{
    protected static $note_store;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$note_store = $this->client->get_note_client();
    }
    
    
    
    public function get_state( $with_metrics = false )
    {
        $access_key = $this->client->get_access_key();
        
        if ( $with_metrics ){
            return self::$note_store->getSyncStateWithMetrics( $access_key, $metrics );
        }else{
            return self::$note_store->getSyncState( $access_key );
        }
    }
    
    public function get_state_with_metrics( )
    {
        return $this->get_state( true );
    }
    
    public function get_chunk( $after_usn, $max_entries = 100, $full_sync_only = false  )
    {
        $access_key = $this->client->get_access_key();
        
        return self::$note_store->getSyncChunk( $access_key, $after_usn, $max_entries, $full_sync_only );
    }
    
    public function get_filtered_chunk()
    {
        // Stub
    }
    
    public function get_linked_notebook_sync_state()
    {
        // Stub
    }
    
    public function get_linked_notebook_sync_chunk()
    {
        // Stub
    }
    

}