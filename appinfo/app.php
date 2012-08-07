<?php

/**
* ownCloud - zarafa
*/
require_once('apps/zarafa/user_zarafa.php');

$l=new OC_L10N('zarafa');

OCP\App::registerAdmin('zarafa','settings');
OC_App::register( array(
	'order' => 50,
	'id' => 'zarafa',
	'name' => 'Zarafa WebApp' )
);

// define Zarafa defaults
define('OC_APP_ZARAFA_DEFAULT_SERVER', 'file:///var/run/zarafa');
define('OC_APP_ZARAFA_DEFAULT_USER_NAME', '');
define('OC_APP_ZARAFA_DEFAULT_USER_PASS', '');
define('OC_APP_ZARAFA_DEFAULT_WEBAPP_URL', 'https://demo.zarafa.com');

OC_App::addNavigationEntry( array(
    'id' => 'zarafa_index',
    'order' => 10,
    'href' => OC_Helper::linkTo('zarafa', 'index.php'),
    'icon' => OC_Helper::imagePath( 'zarafa', 'zarafa.png' ),
    'name' => $l->t('Zarafa WebApp'))
);

// register user backend
OC_User::useBackend( 'ZARAFA' );

