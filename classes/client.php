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

use EDAM\UserStore\UserStoreClient,
    EDAM\NoteStore\NoteStoreClient;

function getCallbackUrl()
{
    $thisUrl = \Fuel\Core\Input::server('HTTPS') ? "https://" : "http://";
    $thisUrl .= \Fuel\Core\Input::server('SERVER_NAME');
    $thisUrl .= (\Fuel\Core\Input::server('SERVER_PORT') == 80 || \Fuel\Core\Input::server('SERVER_PORT') == 443) ? "" : (":".\Fuel\Core\Input::server('SERVER_PORT'));
    $thisUrl .= '/evernote/login';
    $thisUrl .= '?action=callback';
    return $thisUrl;
}

class Client {

	protected $connection = null;
	protected $tokens = array();
	protected $auth_url             = 'https://sandbox.evernote.com/OAuth.action';
	protected $request_token_url    = 'https://sandbox.evernote.com/oauth';
	protected $access_token_url     = 'https://sandbox.evernote.com/oauth';
	protected $signature_method     = 'HMAC-SHA1';
	protected $version              = '1.23';
	protected static $note_store_host      = 'sandbox.evernote.com';
    protected static $note_store_port      = '443';
    protected static $note_store_protocol  = 'https';


	protected $callback = null;
	protected $errors = array();
	protected $enable_debug = false;
    protected static $session_name = 'evernote_oauthtokens';

    protected $user_store = null;
    protected $note_store = null;


	/**
	 * Loads in the Evernote config and sets everything up.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$config = \Config::load('evernote', true);

		$this->tokens = array(
			'consumer_key' 		=> $config[$config['active']]['consumer_key'],
			'consumer_secret' 	=> $config[$config['active']]['consumer_secret'],
			'access_key'		=> $this->get_access_key(),
			'access_secret' 	=> $this->get_access_secret(),
            'shard_id'          => $this->get_shard_id(),
            'evernote_user_id'  => $this->get_evernote_user_id(),
            'expires'           => $this->get_expires()
		);

        if ( isset( $config[$config['active']]['evernote_server'] ) && !empty( $config[$config['active']]['evernote_server'] ) ){
            $this->auth_url             = $config[$config['active']]['evernote_server'] . '/OAuth.action';
            $this->request_token_url    = $config[$config['active']]['evernote_server'] . '/oauth';
            $this->access_token_url     = $this->request_token_url;
        }

        if ( isset( $config[$config['active']]['notestore_host'] ) && !empty( $config[$config['active']]['notestore_host'] ) ){
            self::$note_store_host = $config[$config['active']]['notestore_host'];
        }

        if ( isset( $config[$config['active']]['notestore_port'] ) && !empty( $config[$config['active']]['notestore_port'] ) ){
            self::$note_store_port = $config[$config['active']]['notestore_port'];
        }

        if ( isset( $config[$config['active']]['notestore_protocol'] ) && !empty( $config[$config['active']]['notestore_protocol'] ) ){
            self::$note_store_protocol = $config[$config['active']]['notestore_protocol'];
        }

		$this->check_login();
	}

	/**
	 * If Debug mode is enabled and there are errors, it will display
	 * them.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		if ( ! $this->enable_debug)
		{
			return;
		}

		if ( ! empty($this->errors))
		{
			foreach ($this->errors as $key => $e)
			{
				echo '<pre>'.$e.'</pre>';
			}
		}
	}

	/**
	 * Enables/Disables debug mode.
	 *
	 * @param   bool  $debug  Debug mode
	 * @return  $this
	 */
	public function enable_debug($debug)
	{
		$this->enable_debug = (bool) $debug;
		return $this;
	}



	/**
	 * Checks if the user it logged in through Twitter.
	 *
	 * @return  bool  If the user is logged in
	 */
	public function logged_in()
	{
		$access_key = $this->get_access_key();
		$access_secret = $this->get_access_secret();

		$logged_in = false;

		if ($this->get_access_key() !== null && $this->get_access_secret() !== null)
		{
			$logged_in = true;
		}

		return $logged_in;
	}

	/**
	 * Checks to make sure the Oauth token and access tokens are correct.
	 * Redirects to the current page (refresh)
	 *
	 * @return  null
	 */
	protected function check_login()
	{
		if (isset($_GET['oauth_token']))
		{

            $this->handle_callback();

			$tokens = $this->get_access_token();

            if ( ! empty($tokens['access_key']) && ! empty($tokens['oauth_token'] ) )
			{

                $this->set_shard_id( $tokens['shard_id'] );
                $this->set_evernote_user_id( $tokens['evernote_user_id'] );
                $this->set_expires( $tokens['expires'] );
			}

			\Response::redirect(\Uri::current());
			return null;
		}

	}

	/**
	 * Starts the login process.
	 *
	 * @return  null
	 */
	public function login()
	{

		if ( ( $this->get_access_key() === null || $this->get_access_secret() === null ) )
		{
            echo 'case 1';
            $oauth = new \OAuth( $this->tokens['consumer_key'], $this->tokens['consumer_secret'] );

            $request_token_info = $oauth->getRequestToken( $this->request_token_url, $this->get_callback() );

            if ($request_token_info)
            {

                $this->set_request_tokens( $request_token_info );
            }

            // Now we have the temp credentials, let's get the real ones.

            // Now let's get the OAuth token
			\Response::redirect( $this->get_auth_url() );
            return;
		}

		return $this->check_login();
	}

	/**
	 * Logs the user out.
	 *
	 * @return  this
	 */
	public function logout()
	{
		\Session::delete( self::$session_name );
		return $this;
	}


    public function handle_callback()
    {
        $oauth_verifier = \Fuel\Core\Input::get('oauth_verifier');

        if ( isset( $oauth_verifier ) ) {
            \Session::set('evernote_oauthVerifier', $oauth_verifier);
            return true;
        } else {
            return false;
        }
    }




	/**
	 * Gets the Oauth tokens.
	 *
	 * @return  array  All of the Oauth tokens
	 */
	public function get_tokens()
	{
		return $this->tokens;
	}

	/**
	 * Gets the Consumer Key
	 *
	 * @return  string  The Consumer Key
	 */
	public function get_consumer_key()
	{
		return $this->tokens['consumer_key'];
	}

	/**
	 * Gets the Consumer Secret
	 *
	 * @return  string  The Consumer Secret
	 */
	public function get_consumer_secret()
	{
		return $this->tokens['consumer_secret'];
	}

	/**
	 * Gets the Access Key from the Session.
	 *
	 * @return  string|null  The Access Key
	 */
	public function get_access_key()
	{
        return self::get_value( 'access_key' );
	}

	/**
	 * Gets the Access Secret from the Session.
	 *
	 * @return  string|null  The Access Secret
	 */
	public function get_access_secret()
	{
        return self::get_value( 'access_secret' );
	}

    public function get_request_token()
	{
        return self::get_value( 'request_token' );
	}

    public function get_request_secret()
	{
        return self::get_value( 'request_secret' );
	}

    public function get_shard_id(){
        return self::get_value( 'shard_id' );
    }

    public function get_evernote_user_id(){
        return self::get_value( 'evernote_user_id' );
    }

    public function get_expires(){
        return self::get_value( 'expires' );
    }


    public static function get_value( $key, $session_name = null ){

        if ( ! $session_name ){ $session_name = self::$session_name; }

        $tokens = \Session::get( $session_name );
		return ($tokens === false || ! isset($tokens[ $key ]) || empty($tokens[ $key ])) ? null : $tokens[ $key ];
    }

    public function set_value( $key, $value, $session_name = null)
    {
        if ( ! $session_name ){ $session_name = self::$session_name; }

        $tokens = \Session::get( $session_name );

		if ($tokens === false || ! is_array( $tokens ) ) {
			$tokens = array( $key => $value );
		} else {
			$tokens[ $key ] = $value;
		}

		\Session::set( $session_name, $tokens);

		return $this;
    }


    /**
	 * Sets the access key in the session
	 *
	 * @param   string  $access_key  The access key
	 * @return  $this
	 */
	public function set_access_key($access_key)
	{
		return $this->set_value('access_key', $access_key );
	}


    public function set_request_token( $request_token )
    {
        return $this->set_value('request_token', $request_token );
    }


    public function set_request_secret( $request_secret )
    {
        return $this->set_value('request_secret', $request_secret );
    }


	public function set_shard_id ($shard_id )
	{
		return $this->set_value('shard_id', $shard_id );
	}


	public function set_evernote_user_id( $evernote_user_id )
	{
		return $this->set_value('evernote_user_id', $evernote_user_id );
	}

	public function set_expires( $expires )
	{
		return $this->set_value('expires', $expires );
	}

	/**
	 * Sets the access secret in the session
	 *
	 * @param   string  $access_secret  The access secret
	 * @return  $this
	 */
	public function set_access_secret($access_secret)
	{
        return $this->set_value('access_secret', $access_secret );
	}


	/**
	 * Sets the access tokens.
	 *
	 * Expects: array('oauth_token' => '', 'oauth_token_secret' => '')
	 *
	 * @param   array  $tokens  The access tokens
	 * @return  $this
	 */
	public function set_access_tokens($tokens)
	{
		$this->set_access_key( $tokens['oauth_token'] );
		$this->set_access_secret( $tokens['oauth_token_secret'] );

		return $this;
	}


    public function set_request_tokens( $tokens )
    {
        $this->set_request_token( $tokens['oauth_token'] );
		$this->set_request_secret( $tokens['oauth_token_secret'] );

		return $this;
    }

	/**
	 * Gets the authentication URL
	 *
	 * @return  string  The authentication URL
	 */
	public function get_auth_url()
	{
		return $this->auth_url.'?oauth_token='.$this->get_request_token();
	}


	/**
	 * Gets the access token from Evernote
	 *
	 * @return  string  The access token
	 */
	protected function get_access_token()
	{
        $result = array();

        try
        {
            $oauth_verifier = \Session::get('evernote_oauthVerifier');

            $oauth = new \OAuth( $this->tokens['consumer_key'], $this->tokens['consumer_secret'] );

            $request_token = $this->get_request_token();
            $request_token_secret = $this->get_request_secret();

            $oauth->setToken($request_token, $request_token_secret);
            $access_token_info = $oauth->getAccessToken($this->access_token_url, null, $oauth_verifier);

            echo '$access_token_info<pre>'.print_r($access_token_info, 1).'</pre>';

            if ( $access_token_info ){
                $this->set_access_key( $access_token_info['oauth_token'] );
                $this->set_access_secret( $access_token_info['oauth_token_secret'] );

                $this->tokens['oauth_token']            = $access_token_info['oauth_token'];
                $this->tokens['oauth_token_secret']     = $access_token_info['oauth_token_secret'];
                $this->tokens['shard_id']               = $access_token_info['edam_shard'];
                $this->tokens['evernote_user_id']       = $access_token_info['edam_userId'];
                $this->tokens['expires']                = $access_token_info['edam_expires'];

                return $this->tokens;
            }
            return false;
        }catch ( \Exception $e ){
            return false;
        }

	}

	/**
	 * Sends the request to Evernote and returns the response.
	 *
	 * @param   string  $method  The HTTP method
	 * @param   string  $url     The URL of the request
	 * @param   array   $params  The request parameters
	 * @return  mixed   The response
	 */
	protected function http_request($method = null, $url = null, $params = null)
	{
		if (empty($method) || empty($url))
		{
			return false;
		}

		if (empty($params['oauth_signature']))
		{
			$params = $this->prep_params($method, $url, $params);
		}

		$this->connection = new \Evernote_Connection();

		try
		{
			switch ($method)
			{
				case 'GET':
					return $this->connection->get($url, $params);
				break;

				case 'POST':
					return $this->connection->post($url, $params);
				break;

				case 'PUT':
					return null;
				break;

				case 'DELETE':
					return null;
				break;
			}
		}
		catch (\EvernoteException $e)
		{
			$this->errors[] = $e;
			return array("error" => $e->getMessage());
		}
	}

	/**
	 * Gets the callback URL.
	 *
	 * @return  string  The callback URL
	 */
	public function get_callback()
	{
		return $this->callback;
	}

	/**
	 * Sets the callback URL.
	 *
	 * @param   string  $url  The callback URL
	 * @return  $this
	 */
	public function set_callback($url)
	{
		$this->callback = $url;
		return $this;
	}

	/**
	 * Generates the parameters needed for a request.
	 *
	 * @param   string  $method  The HTTP method
	 * @param   string  $url     The URL of the request
	 * @param   array   $params  The request parameters
	 * @return  array   The params
	 */
	protected function prep_params($method = null, $url = null, $params = null)
	{
		if (empty($method) || empty($url))
		{
			return false;
		}

		$callback = $this->get_callback();
		if ( ! empty($callback))
		{
			$oauth['oauth_callback'] = $callback;
		}

		$this->set_callback(null);

		$oauth['oauth_consumer_key']      = $this->get_consumer_key();
		$oauth['oauth_token']             = $this->get_access_key();
		$oauth['oauth_nonce']             = $this->generate_nonce();
		$oauth['oauth_timestamp']         = time();
		$oauth['oauth_signature_method']  = $this->signature_method;
		$oauth['oauth_version']           = $this->version;

		array_walk($oauth, array($this, 'encode_rfc3986'));

		if (is_array($params))
		{
			array_walk($params, array($this, 'encode_rfc3986'));
		}

		$encodedParams = array_merge($oauth, (array)$params);

		ksort($encodedParams);

		$oauth['oauth_signature'] = $this->encode_rfc3986($this->generate_signature($method, $url, $encodedParams));
		return array('request' => $params, 'oauth' => $oauth);
	}

	/**
	 * Generates a security nonce
	 *
	 * @return  string  The nonce
	 */
	protected function generate_nonce()
	{
		return md5(uniqid(rand(), true));
	}

	/**
	 * Encodes the given string according to RFC3986
	 *
	 * @param   string  $string  The string to encode
	 * @return  string  The encoded string
	 */
	protected function encode_rfc3986($string)
	{
		return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode(($string))));
	}

	/**
	 * Generates a signature for the request.
	 *
	 * @param   string  $method  The HTTP method
	 * @param   string  $url     The request URL
	 * @param   array   $params  The request parameters
	 * @return  string  The signature
	 */
	protected function generate_signature($method = null, $url = null, $params = null)
	{
		if (empty($method) || empty($url))
		{
			return false;
		}

		// concatenating
		$concat_params = '';

		foreach ($params as $k => $v)
		{
			$v = $this->encode_rfc3986($v);
			$concat_params .= "{$k}={$v}&";
		}

		$concat_params = $this->encode_rfc3986(substr($concat_params, 0, -1));

		// normalize url
		$normalized_url = $this->encode_rfc3986($this->normalize_url($url));
		$method = $this->encode_rfc3986($method); // don't need this but why not?

		return $this->sign_string("{$method}&{$normalized_url}&{$concat_params}");
	}

	/**
	 * Normalizes a given URL so that it is the proper format.
	 *
	 * @param   string  $url  The URL to normalize
	 * @return  string  The normalized URL
	 */
	protected function normalize_url($url = null)
	{
		$url_parts = parse_url($url);

		$url_parts['port'] = isset($url_parts['port']) ? $url_parts['port'] : 80;

		$scheme = strtolower($url_parts['scheme']);
		$host = strtolower($url_parts['host']);
		$port = intval($url_parts['port']);

		$retval = "{$scheme}://{$host}";

		if ($port > 0 && ( $scheme === 'http' && $port !== 80 ) || ( $scheme === 'https' && $port !== 443 ))
		{
			$retval .= ":{$port}";
		}

		$retval .= $url_parts['path'];

		if ( !empty($url_parts['query']) )
		{
			$retval .= "?{$url_parts['query']}";
		}

		return $retval;
	}

	/**
	 * Generates the signature.
	 *
	 * @return  string  The signature
	 */
	protected function sign_string($string)
	{
		$retval = false;
		switch ($this->signature_method)
		{
			case 'HMAC-SHA1':
				$key = $this->encode_rfc3986($this->get_consumer_secret()) . '&' . $this->encode_rfc3986($this->get_access_secret());
				$retval = base64_encode(hash_hmac('sha1', $string, $key, true));
			break;
		}

		return $retval;
	}



    public function set_user_store( $user_store )
    {
        $this->user_store = $user_store;
        return $this->user_store;
    }


    protected function get_user_store()
    {

        if ( $this->user_store ){
            return $this->user_store;
        }

        $userStoreHttpClient =  new \THttpClient( self::$note_store_host, self::$note_store_port, 'edam/user', self::$note_store_protocol );

        $userStoreProtocol = new \TBinaryProtocol( $userStoreHttpClient );

        $user_store = new UserStoreClient( $userStoreProtocol, $userStoreProtocol );

        $this->set_user_store( $user_store );

        return $user_store;
    }


    protected function get_note_store()
    {

        if ( $this->note_store ){
            return $this->note_store;
        }

        $user_store = $this->get_user_store();
        $note_store_url = $user_store->getNoteStoreUrl( $this->get_access_key() );

        // Now let's examine the note store URL, then we can init the note store.
        $parts = parse_url($note_store_url);

        if (!isset($parts['port'])) {
            if ($parts['scheme'] === 'https') {
                self::$note_store_port = 443;
            } else {
                self::$note_store_port = 80;
            }
        }

        self::$note_store_host = $parts['host'];
        self::$note_store_protocol = $parts['scheme'];

        $note_store_http_client =  new \THttpClient( self::$note_store_host, self::$note_store_port, $parts['path'], self::$note_store_protocol );

        $note_store_protocol = new \TBinaryProtocol( $note_store_http_client );

        $note_store = new NoteStoreClient( $note_store_protocol, $note_store_protocol );

        $this->set_note_store( $note_store );

        return $note_store;
    }


    public function set_note_store( $note_store )
    {
        $this->note_store = $note_store;
        return $this->note_store;
    }



    public function get_user_client()
    {
        return $this->get_user_store();
    }

    public function get_note_client()
    {
        return $this->get_note_store();
    }



    /**
     * @param string $name
     *
     * @return Api\Api_Interface
     *
     * @throws Exception_Argument_Invalid
     */
    public function api($name)
    {
        // Only load the API classes if it is not already loaded.
        if (!isset($this->apis[$name])) {
            switch ($name) {
                case 'user':
                    $api = new Api\User( $this );
                    break;

                case 'sync':
                    $api = new Api\Sync( $this );
                    break;

                case 'note':
                case 'notes':
                    $api = new Api\Note( $this );
                    break;

                case 'notebook':
                case 'notebooks':
                    $api = new Api\Notebook( $this );
                    break;

                case 'tag':
                case 'tags':
                    $api = new Api\Tag( $this );
                    break;

                case 'search':
                    $api = new Api\Search( $this );
                    break;

                case 'resource':
                case 'resources':
                    $api = new Api\Resource( $this );
                    break;

                default:
                    throw new Exception_Argument_Invalid();
            }

            $this->apis[$name] = $api;
        }

        return $this->apis[$name];
    }

}
