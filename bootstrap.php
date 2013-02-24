<?php

Autoloader::add_core_namespace('Evernote');

Autoloader::add_classes(array(
	'Evernote\\Evernote'                => __DIR__.'/classes/evernote.php',
	'Evernote\\Client'                  => __DIR__.'/classes/client.php',
    'Evernote\\Evernote_Exception'      => __DIR__.'/classes/evernote.php',
	'Evernote\\Evernote_Connection'     => __DIR__.'/classes/evernote/connection.php',
	'Evernote\\Evernote_Oauth_Response' => __DIR__.'/classes/evernote/oauth/response.php',
    
    'Evernote\\Api\\Api_Interface'      => __DIR__.'/classes/api/api_interface.php',
    'Evernote\\Api\\Abstract_Api'       => __DIR__.'/classes/api/abstract/api.php',
    
    'Evernote\\Api\\User'               => __DIR__.'/classes/api/user.php',
    'Evernote\\Api\\User\\Authenticate' => __DIR__.'/classes/api/user/authenticate.php',
    
    
    /** ------- Thrift files ------- */
    'TType'                             => __DIR__.'/lib/Thrift.php',
    'TMessageType'                      => __DIR__.'/lib/Thrift.php',
    'TException'                        => __DIR__.'/lib/Thrift.php',
    'TBase'                             => __DIR__.'/lib/Thrift.php',
    'TApplicationException'             => __DIR__.'/lib/Thrift.php',
    'TException'                        => __DIR__.'/lib/Thrift.php',
    
    'TTransportException'               => __DIR__.'/lib/transport/TTransport.php',
    'TTransport'                        => __DIR__.'/lib/transport/TTransport.php',
    
    'THttpClient'                       => __DIR__.'/lib/transport/THttpClient.php',
    
    'TProtocolException'                => __DIR__.'/lib/protocol/TProtocol.php',
    'TProtocol'                         => __DIR__.'/lib/protocol/TProtocol.php',
    
    'TBinaryProtocol'                   => __DIR__.'/lib/protocol/TBinaryProtocol.php',
    'TBinaryProtocolFactory'            => __DIR__.'/lib/protocol/TBinaryProtocol.php',
    'TBinaryProtocolAccelerated'        => __DIR__.'/lib/protocol/TBinaryProtocol.php',
    
    'EDAM\Types\Data'                   => __DIR__.'/lib/packages/Types/Types_types.php',
    'EDAM\Types\Note'                   => __DIR__.'/lib/packages/Types/Types_types.php',
    'EDAM\Types\Resource'               => __DIR__.'/lib/packages/Types/Types_types.php',
    'EDAM\Types\ResourceAttributes'     => __DIR__.'/lib/packages/Types/Types_types.php',
    
    'EDAM\UserStore\UserStoreClient'    => __DIR__.'/lib/packages/UserStore/UserStore.php',
    
    'EDAM\NoteStore\NoteStoreClient'    => __DIR__.'/lib/packages/NoteStore/NoteStore.php',
    
    'EDAM\Error\EDAMUserException'      => __DIR__.'/lib/packages/Errors/Errors_types.php',
    'EDAM\Error\EDAMErrorCode'          => __DIR__.'/lib/packages/Errors/Errors_types.php',
   
    
));

// define( 'THRIFT_ROOT', __DIR__.'/../lib' );


