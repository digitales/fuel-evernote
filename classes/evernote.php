<?php

/**
 * Fuel Evernote Package
 * 
 * Inspired by Elliot Haughin's CodeIgniter Twitter library.
 *
 * @copyright  2013 Ross Tweedie
 * @license    MIT License
 */

namespace Evernote;

class Evernote_Exception extends \Exception {}

class Evernote {
    
    
    /**
     * The list of loaded API instances
     *
     * @var array
     */
    private $apis = array();
    

	private function __construct() { }

	/**
	 * @var  string  $version  The current version of the package
	 */
	public static $version = '1.0';

	/**
	 * @var  Evernote_Oauth  $oauth  Holds the Evernote_Oauth instance.
	 */
	protected static $client = null;

	/**
	 * Creates the Evernote_Oauth instance
	 *
	 * @return  void
	 */
	public static function _init()
	{
		static::$client  = new \Evernote\Client();
	}

	/**
	 * Magic pass-through to the Evernote_Oauth instance.
	 *
	 * @param   string  $method  The called method
	 * @param   array   $args    The method arguments
	 * @return  mixed   The method results
	 * @throws  BadMethodCallException
	 */
	public static function __callStatic($method, $args)
	{
		if (is_callable(array(static::$client, $method)))
		{
			return call_user_func_array(array(static::$client, $method), $args);
		}

		throw new \BadMethodCallException("Method Evernote::$method does not exist.");
	}

	/**
	 * Gets the Oauth access tokens.
	 *
	 * @return  array  The access tokens
	 */
	public static function get_tokens()
	{
		return static::$client->get_tokens();
	}

    
	/**
	 * An alias for Evernote_Oauth::set_access_tokens.
	 *
	 * @param   array  $tokens  The access tokens
	 * @return  Evernote_Oauth
	 */
	public static function set_tokens($tokens)
	{
        $tokens_to_pass = array();
        
        foreach( $tokens AS $token_key => $token_value ){
            switch( $token_key ){
                case 'oauth_token':
                case 'token':
                    $tokens_to_pass['oauth_token'] = $token_value;
                    break;
                case 'oauth_token_secret':
                case 'oauth_secret':
                case 'secret':
                    $tokens_to_pass['oauth_token_secret'] = $token_value;
                    break;
                default:
                    continue;
                    break;
            }   
        }        
		return static::$client->set_access_tokens( $tokens_to_pass );
	}
    
}