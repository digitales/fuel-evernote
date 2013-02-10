<?php

Autoloader::add_core_namespace('Evernote');

Autoloader::add_classes(array(
	'Evernote\\Evernote'                 => __DIR__.'/classes/evernote.php',
	'Evernote\\EvernoteException'        => __DIR__.'/classes/evernote.php',
	'Evernote\\Evernote_Connection'      => __DIR__.'/classes/evernote/connection.php',
	'Evernote\\Evernote_Oauth'           => __DIR__.'/classes/evernote/oauth.php',
	'Evernote\\Evernote_Oauth_Response'  => __DIR__.'/classes/evernote/oauth/response.php',
));
