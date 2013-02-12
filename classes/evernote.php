<?php

/**
 * Fuel Evernote Package
 *
 * This is a port of Elliot Haughin's CodeIgniter Twitter library.
 * You can find his library here http://www.haughin.com/code/twitter/
 *
 * @copyright  2011 Dan Horrigan
 * @license    MIT License
 */

namespace Evernote;

class EvernoteException extends \Exception {}

class Evernote {

	/**
	 * Oh noz! a Singleton!!!
	 */
	private function __construct() { }

	/**
	 * @var  string  $version  The current version of the package
	 */
	public static $version = '1.0';

	/**
	 * @var  Evernote_Oauth  $oauth  Holds the Evernote_Oauth instance.
	 */
	protected static $oauth = null;

	/**
	 * Creates the Evernote_Oauth instance
	 *
	 * @return  void
	 */
	public static function _init()
	{
		static::$oauth = new \Evernote_Oauth();
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
		if (is_callable(array(static::$oauth, $method)))
		{
			return call_user_func_array(array(static::$oauth, $method), $args);
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
		return static::$oauth->get_tokens();
	}

	/**
	 * An alias for Evernote_Oauth::set_access_tokens.
	 *
	 * @param   array  $tokens  The access tokens
	 * @return  Evernote_Oauth
	 */
	public static function set_tokens($tokens)
	{
		return static::$oauth->set_access_tokens($tokens);
	}
    
    
    public static function get_user()
    {
        $tokens = static::get_tokens();
        
        $user_store = static::$oauth->get_user_client( );
        
        return $user_store->getUser( $tokens['access_key'] );
        
    }
    
    
}