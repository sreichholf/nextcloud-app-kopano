<?php

/**
* ownCloud - External plugin
*
* @author Frank Karlitschek
* @copyright 2011 Frank Karlitschek karlitschek@kde.org
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either 
* version 3 of the License, or any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*  
* You should have received a copy of the GNU Lesser General Public 
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
* 
*/

require_once('lib/base.php');

// Check if we are a user
OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('zarafa');
OCP\App::setActiveNavigationEntry( 'zarafa_index' );

$tmpl = new OCP\Template( 'zarafa', 'frame', 'user' );
$url = OCP\Config::getAppValue('zarafa', 'zarafa_webapp_url', OC_APP_ZARAFA_DEFAULT_WEBAPP_URL);
$tmpl->assign('url', $url);
$tmpl->printPage();

?>
