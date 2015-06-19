<?php

/**
* ownCloud - zarafa
*/
require_once('apps/zarafa/user_zarafa.php');

$l= \OC::$server->getL10N('zarafa');

OCP\App::registerAdmin('zarafa','settings');

// define Zarafa defaults
define('OC_APP_ZARAFA_DEFAULT_SERVER', 'file:///var/run/zarafa');
define('OC_APP_ZARAFA_DEFAULT_USER_NAME', '');
define('OC_APP_ZARAFA_DEFAULT_USER_PASS', '');
define('OC_APP_ZARAFA_DEFAULT_WEBAPP_URL', 'https://demo.zarafa.com');

\OC::$server->getNavigationManager()->add(
	[
		'id' => 'zarafa_index',
		'order' => 10,
		'href' => OCP\Util::linkTo('zarafa', 'index.php'),
		'icon' => OCP\Util::imagePath( 'zarafa', 'zarafa.png' ),
		'name' => $l->t('Zarafa')
	]
);

// register user backend
OC_User::useBackend( 'ZARAFA' );

