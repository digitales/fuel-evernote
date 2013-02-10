<?php
/**
 * Enter your API keys below.  You get your API keys by creating an
 * app on http://dev.evernote.com/documentation/cloud
 */
return array(
	'active' => Fuel::$env,

	'development' => array(
		'consumer_key'     => '',
		'consumer_secret'  => '',
        'evernote_server'           => 'https://sandbox.evernote.com',
        'notestore_host'            => 'sandbox.evernote.com',
        'notestore_port'            => '443',
        'notestore_protocol'        => 'https',
	),
	'production' => array(
		'consumer_key'     => isset($_SERVER['EVERNOTE_CONSUMER_KEY']) ? $_SERVER['EVERNOTE_CONSUMER_KEY'] : null,
		'consumer_secret'  => isset($_SERVER['EVERNOTE_CONSUMER_SECRET']) ? $_SERVER['EVERNOTE_CONSUMER_SECRET'] : null,
        'evernote_server' => 'https://sandbox.evernote.com',
        'notestore_host' => 'sandbox.evernote.com',
        'notestore_port' => '443',
        'notestore_protocol' => 'https',
	),
);
