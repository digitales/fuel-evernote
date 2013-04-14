<?php
namespace Evernote\Api;

use Evernote\Api\Abstract_Api;
/*
 public function findNotes($authenticationToken, $filter, $offset, $maxNotes);
  public function findNoteOffset($authenticationToken, $filter, $guid);
  public function findNotesMetadata($authenticationToken, $filter, $offset, $maxNotes, $resultSpec);
  public function findNoteCounts($authenticationToken, $filter, $withTrash);
  public function getNote($authenticationToken, $guid, $withContent, $withResourcesData, $withResourcesRecognition, $withResourcesAlternateData);
  public function getNoteContent($authenticationToken, $guid);
  public function getNoteSearchText($authenticationToken, $guid, $noteOnly, $tokenizeForIndexing);
  public function getResourceSearchText($authenticationToken, $guid);
  public function getNoteTagNames($authenticationToken, $guid);
  public function createNote($authenticationToken, $note);
  public function updateNote($authenticationToken, $note);
  public function deleteNote($authenticationToken, $guid);
  public function expungeNote($authenticationToken, $guid);
  public function expungeNotes($authenticationToken, $noteGuids);
  public function expungeInactiveNotes($authenticationToken);
  public function copyNote($authenticationToken, $noteGuid, $toNotebookGuid);
  public function listNoteVersions($authenticationToken, $noteGuid);
  public function getNoteVersion($authenticationToken, $noteGuid, $updateSequenceNum, $withResourcesData, $withResourcesRecognition, $withResourcesAlternateData);
  
  
  notefilter
  notebookGuid
  */
/**
 * Getting information regarding the notes
 *
 * @author Ross Tweedie <ross.tweedie at gmail dot com>
 */
class Note extends Abstract_Api
{
    protected static $note_store, $filter, $note;
    
    function __construct( $client = null )
    {
        parent::__construct( $client );
        
        static::$note_store = $this->client->get_note_client();
        static::$filter = new \Edam\NoteStore\NoteFilter();
    }
    
    /**
     * Forge / create a new note
     *
     * @param array $note
     * @return EDAM\Types\Note
     */
    function forge( $note = null )
    {
        static::$note = new \EDAM\Types\Note( $note );
        
        return static::$note;
    }
    
    public function find( $filter = null, $offset = 0, $max_notes = 100 )
    {
        
        return self::$note_store->findNotes($this->client->get_access_key(), $filter, $offset, $max_notes);
    }
    
    public function find_for_notebook( $guid, $offset = 0, $max_notes = 100 )
    {
        self::$filter->notebookGuid = $guid;
        
        return $this->find( self::$filter, $offset, $max_notes );
    }
    
    public function find_offset( $filter, $guid )
    {
        return self::$note_store->findNoteOffset( $this->client->get_access_key(), $filter, $guid);
    }
    
    public function find_metadata( $filter, $offset, $max_notes, $result_spec ){
        return self::$note_store->findNotesMetadata( $this->client->get_access_key(), $filter, $offset, $max_notes, $result_spec );
    }
    
    public function find_counts( $filter, $with_trash )
    {
        return self::$note_store->findNoteCounts( $this->client->get_access_key(), $filter, $with_trash );
    }
    
    
    /*
     * Get a note.
     *
     * Returns the current state of the note in the service with the provided GUID. The ENML contents of the note will only be provided if the 'withContent' parameter is true. The service will include the meta-data for each resource in the note, but the binary contents of the resources and their recognition data will be omitted. If the Note is found in a public notebook, the authenticationToken will be ignored (so it could be an empty string). The applicationData fields are returned as keysOnly.
     *
     * @param  guid The GUID of the note to be retrieved.
     * @param  withContent If true, the note will include the ENML contents of its 'content' field.
     * @param  withResourcesData If true, any Resource elements in this Note will include the binary contents of their 'data' field's body.
     * @param  withResourcesRecognition If true, any Resource elements will include the binary contents of the 'recognition' field's body if recognition data is present.
     * @param  withResourcesAlternateData If true, any Resource elements in this Note will include the binary contents of their 'alternateData' fields' body, if an alternate form is present.
     *
     * @return EDAM\Types\Note Object
     *
     * @throws  EDAMUserException
     * BAD_DATA_FORMAT "Note.guid" - if the parameter is missing
     * PERMISSION_DENIED "Note" - private note, user doesn't own
     * @throws  EDAMNotFoundException
     * "Note.guid" - not found, by GUID
    */
    public function get( $guid, $with_content = true, $with_resources_data = false, $with_resources_recognition = false, $with_resources_alternate_data = false)
    {
        return self::$note_store->getNote( $this->client->get_access_key(), $guid, $with_content, $with_resources_data, $with_resources_recognition, $with_resources_alternate_data);
    }
    
    public function get_content( $guid ){
        return self::$note_store->getNoteContent( $this->client->get_access_key(), $guid );
    }
    
    public function get_search_text( $guid, $note_only, $tokenize_for_indexing )
    {
        return self::$note_store->getNoteSearchText( $this->client->get_access_key(), $guid, $note_only, $tokenize_for_indexing);
    }
    
    public function get_resource_search_text( $guid )
    {
        return self::$note_store->getResourceSearchText( $this->client->get_access_key(), $guid);
    }
    
    public function get_tag_names( $guid )
    {
        return self::$note_store->getNoteTagNames( $this->client->get_access_key(), $guid);
    }
    
    public function create( $note )
    {
        return self::$note_store->createNote( $this->client->get_access_key(), $note );
    }
    
    public function update( $note )
    {
        return self::$note_store->updateNote( $this->client->get_access_key(), $note );
    }
    
    //public function delete( $guid )
    //{
    //    return self::$note_store->deleteNote( $this->client->get_access_key(), $guid );
    //}
    
    public function expunge( $guid )
    {
        return self::$note_store->expungeNote( $this->client->get_access_key(), $guid );
    }
    
    public function expunge_notes( $note_guids )
    {
        return self::$note_store->expungeNotes( $this->client->get_access_key(), $note_guids );
    }
    
    public function expunge_inactive()
    {
        return self::$note_store->expungeInactiveNotes( $this->client->get_access_key() );
    }

    public function copy( $note_guid, $to_notebook_guid )
    {
        return self::$note_store->copyNote( $this->client->get_access_key(), $note_guid, $to_notebook_guid );
    }
    
    public function list_versions( $guid )
    {
        return self::$note_store->listNoteVersions( $this->client->get_access_key(), $guid);
    }
    
    public function get_version( $guid, $update_sequence_num, $with_resources_data, $with_resources_recognition, $with_resources_alternative_data )
    {
        return self::$note_store->getNoteVersion( $this->client->get_access_key(), $guid, $update_sequence_num, $with_resources_data,$with_resources_recognition, $with_resources_alternative_data );
    }
    
    public function get_list_by_notebook( $notebook_guid )
    {
        // return self::$note_store->getTagsByNotebook( $this->client->get_access_key(), $notebook_guid );
    }
}