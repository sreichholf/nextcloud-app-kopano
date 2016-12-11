<?php

/**
* nextcloud - kopano integration
*/
require_once('apps/kopano/user_kopano.php');

$l= \OC::$server->getL10N('kopano');

OCP\App::registerAdmin('kopano','admin');

// define Zarafa defaults
define('OC_APP_KOPANO_DEFAULT_SERVER', 'file:///var/run/kopano/server.sock');
define('OC_APP_KOPANO_DEFAULT_USER_NAME', '');
define('OC_APP_KOPANO_DEFAULT_USER_PASS', '');
define('OC_APP_KOPANO_DEFAULT_WEBAPP_URL', 'https://demo.kopano.com');

\OC::$server->getNavigationManager()->add(
	[
		'id' => 'kopano_index',
		'order' => 10,
		'href' => OCP\Util::linkTo('kopano', 'index.php'),
		'icon' => OCP\Util::imagePath( 'kopano', 'kopano.png' ),
		'name' => $l->t('Kopano')
	]
);

// register user backend
OC_User::useBackend( 'KOPANO' );
