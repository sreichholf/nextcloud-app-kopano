<?php

/**
 * ownCloud - kopano
 */
$params = array('kopano_server', 'kopano_user_name', 'kopano_user_pass', 'kopano_webapp_url');

OCP\Util::addscript('kopano', 'settings');

if ($_POST) {
       foreach($params as $param){
               if(isset($_POST[$param])){
                       OCP\Config::setAppValue('kopano', $param, $_POST[$param]);
               }
       }
}

// fill template
$tmpl = new OCP\Template( 'kopano', 'admin');
foreach($params as $param){
               $value = OCP\Config::getAppValue('kopano', $param,'');
               $tmpl->assign($param, $value);
}

// settings with default values
$tmpl->assign( 'kopano_server', OCP\Config::getAppValue('kopano', 'kopano_server', OC_APP_KOPANO_DEFAULT_SERVER));
$tmpl->assign( 'kopano_user_name', OCP\Config::getAppValue('kopano', 'kopano_user_name', OC_APP_KOPANO_DEFAULT_USER_NAME));
$tmpl->assign( 'kopano_user_pass', OCP\Config::getAppValue('kopano', 'kopano_user_pass', OC_APP_KOPANO_DEFAULT_USER_PASS));
$tmpl->assign( 'kopano_webapp_url', OCP\Config::getAppValue('kopano', 'kopano_webapp_url', OC_APP_KOPANO_DEFAULT_WEBAPP_URL));

return $tmpl->fetchPage();
