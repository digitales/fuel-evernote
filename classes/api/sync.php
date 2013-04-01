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
    protected static $note_store, $filter;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$note_store = $this->client->get_note_client();
        static::$filter = new \Edam\NoteStore\SyncChunkFilter();
        self::$filter->includeNotes = true;
        self::$filter->includeNoteAtributes = true;
        self::$filter->includeNotebooks = true;
        self::$filter->includeTags = true;
        self::$filter->includeLinkedNotebook = true;
        self::$filter->includeNoteContentClass = true;
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
    
    /*
     * Get chunk
     *
     * DEPRECATED - use getFilteredSyncChunk.
     */
    public function get_chunk( $after_usn, $max_entries = 100, $full_sync_only = false  )
    {
        $access_key = $this->client->get_access_key(); 
        return self::$note_store->getSyncChunk( $access_key, $after_usn, $max_entries, $full_sync_only );
    }
    
    
    /*
    * Asks the NoteStore to provide the state of the account in order of last modification. This request retrieves one block of the server's state so that a client can make several small requests against a large account rather than getting the entire state in one big message. This call gives fine-grained control of the data that will be received by a client by omitting data elements that a client doesn't need. This may reduce network traffic and sync times.
    *
    * @param  afterUSN The client can pass this value to ask only for objects that have been updated after a certain point. This allows the client to receive updates after its last checkpoint rather than doing a full synchronization on every pass. The default value of "0" indicates that the client wants to get objects from the start of the account.
    * @param  maxEntries The maximum number of modified objects that should be returned in the result SyncChunk. This can be used to limit the size of each individual message to be friendly for network transfer.
    * @param  filter The caller must set some of the flags in this structure to specify which data types should be returned during the synchronization. See the SyncChunkFilter structure for information on each flag.
    */
    public function get_filtered_chunk( $after_usn = 0 , $max_entries = 100, $filter = null )
    {
        $access_key = $this->client->get_access_key();
        
        return self::$note_store->getFilteredSyncChunk($access_key, $after_usn, $max_entries, self::$filter);
    }
    
    
    public function get_notebook_chunk( $guid, $after_usn = 0 , $max_entries = 100 )
    {        
        return $this->get_filtered_chunk( $after_usn, $max_entries, self::$filter );

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