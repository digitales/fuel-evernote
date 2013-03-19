<?php

namespace Evernote\Api;

use Evernote\Client;

use Fuel\Core\Request;
use Fuel\Core\Request_Curl;

/**
 * Abstract class for Api classes
 *
 * @author Ross Tweeddie <r.tweedie at gmail dot com>
 */
abstract class Abstract_Api implements Api_Interface
{
    
    const UNKNOWN = 'No information available about the error';
    const BAD_DATA_FORMAT ='The format of the request data was incorrect';
    const PERMISSION_DENIED = 'Not permitted to perform action';
    const INTERNAL_ERROR = 'Unexpected problem with the service';
    const DATA_REQUIRED = 'A required parameter/field was absent';
    const LIMIT_REACHED = 'Operation denied due to data model limit';
    const QUOTA_REACHED = 'Operation denied due to user storage limit';
    const INVALID_AUTH = 'Username and/or password incorrect';
    const AUTH_EXPIRED = 'Authentication token expired';
    const DATA_CONFLICT = 'Change denied due to data model conflict';
    const ENML_VALIDATION = 'Content of submitted note was malformed';
    const SHARD_UNAVAILABLE = 'Service shard with account data is temporarily down';
    const LEN_TOO_SHORT = 'Operation denied due to data model limit, where something such as a string length was too short';
    const LEN_TOO_LONG = 'Operation denied due to data model limit, where something such as a string length was too long';
    const TOO_FEW = 'Operation denied due to data model limit, where there were too few of something.';
    const TOO_MANY = 'Operation denied due to data model limit, where there were too many of something.';
    const UNSUPPORTED_OPERATION = 'Operation denied because it is currently unsupported.';

    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct( $client )
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    //protected function get( $path, array $parameters = array(), $requestOptions = array() )
    //{
    //    return $this->client->get( $path, $parameters, $requestOptions );
    //}

    /**
     * {@inheritDoc}
     */
    protected function post( $path, array $parameters = array(), $requestOptions = array() )
    {
        return $this->client->post( $path, $parameters, $requestOptions );
    }

    /**
     * {@inheritDoc}
     */
    protected function patch( $path, array $parameters = array(), $requestOptions = array() )
    {
        return $this->client->patch( $path, $parameters, $requestOptions );
    }

    /**
     * {@inheritDoc}
     */
    protected function put( $path, array $parameters = array(), $requestOptions = array() )
    {
        return $this->client->put( $path, $parameters, $requestOptions );
    }

    /**
     * {@inheritDoc}
     */
    protected function delete( $path, array $parameters = array(), $requestOptions = array() )
    {
        return $this->client->delete( $path, $parameters, $requestOptions );
    }
    
    
    public function  get_error( $error_number, $paramater = null )
    {
        $errors = array(
                1 => 'UNKNOWN',
                2 => 'BAD_DATA_FORMAT',
                3 => 'PERMISSION_DENIED',
                4 => 'INTERNAL_ERROR',
                5 => 'DATA_REQUIRED',
                6 => 'LIMIT_REACHED',
                7 => 'QUOTA_REACHED',
                8 => 'INVALID_AUTH',
                9 => 'AUTH_EXPIRED',
                10 => 'DATA_CONFLICT',
                11 => 'ENML_VALIDATION',
                12 => 'SHARD_UNAVAILABLE',
                13 => 'LEN_TOO_SHORT',
                14 => 'LEN_TOO_LONG',
                15 => 'TOO_FEW',
                16 => 'TOO_MANY',
                17 => 'UNSUPPORTED_OPERATION',
            );
        
        if ( isset( $errors[ $error_number ] ) ){
            return $errors[ $error_number ];
        } else {
            return $errors[1];
        }
    }
}
