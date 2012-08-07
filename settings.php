<?php

/**
 * ownCloud - zarafa
 */
$params = array('zarafa_server', 'zarafa_user_name', 'zarafa_user_pass', 'zarafa_webapp_url');

OCP\Util::addscript('zarafa', 'settings');

if ($_POST) {
       foreach($params as $param){
               if(isset($_POST[$param])){
                       OCP\Config::setAppValue('zarafa', $param, $_POST[$param]);
               }
       }
}

// fill template
$tmpl = new OCP\Template( 'zarafa', 'settings');
foreach($params as $param){
               $value = OCP\Config::getAppValue('zarafa', $param,'');
               $tmpl->assign($param, $value);
}

// settings with default values
$tmpl->assign( 'zarafa_server', OCP\Config::getAppValue('zarafa', 'zarafa_server', OC_APP_ZARAFA_DEFAULT_SERVER));
$tmpl->assign( 'zarafa_user_name', OCP\Config::getAppValue('zarafa', 'zarafa_user_name', OC_APP_ZARAFA_DEFAULT_USER_NAME));
$tmpl->assign( 'zarafa_user_pass', OCP\Config::getAppValue('zarafa', 'zarafa_user_pass', OC_APP_ZARAFA_DEFAULT_USER_PASS));
$tmpl->assign( 'zarafa_webapp_url', OCP\Config::getAppValue('zarafa', 'zarafa_webapp_url', OC_APP_ZARAFA_DEFAULT_WEBAPP_URL));

return $tmpl->fetchPage();
