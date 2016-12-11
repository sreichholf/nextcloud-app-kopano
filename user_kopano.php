OS<?php

/**
 * ownCloud - kopano
 */

include("mapi/mapi.util.php");
include("mapi/mapicode.php");
include("mapi/mapidefs.php");
include("mapi/mapitags.php");

class OC_USER_KOPANO extends OC_User_Backend {
	protected $kopano_session;
	protected $kopano_store;
	protected $kopano_server;
	protected $kopano_users;
	protected $kopano_user_name;
	protected $kopano_user_pass;

	function __construct() {
		$this->kopano_server = OCP\Config::getAppValue('kopano', 'kopano_server', OC_APP_KOPANO_DEFAULT_SERVER);
		$this->kopano_user_name = OCP\Config::getAppValue('kopano', 'kopano_user_name', OC_APP_KOPANO_DEFAULT_USER_NAME);
		$this->kopano_user_pass = OCP\Config::getAppValue('kopano', 'kopano_user_pass', OC_APP_KOPANO_DEFAULT_USER_PASS);
	}

	private function getKopanoSession(){
		$this->kopano_session = mapi_logon_zarafa($this->kopano_user_name, $this->kopano_user_pass, $this->kopano_server, NULL, NULL);
		if($this->kopano_session)
			return true;
		OCP\Util::writeLog('OC_USER_KOPANO', "Cannot get kopano session", 3);
		return false;
	}

	private function getDefaultStore(){
		if(!$this->kopano_store){
			if($this->getKopanoSession()){	
				$msgStoresTable = mapi_getmsgstorestable($this->kopano_session);
				$msgStores = mapi_table_queryallrows($msgStoresTable, array(PR_DEFAULT_STORE, PR_ENTRYID));
				$storeEntryId = false;
				foreach($msgStores as $row){
					if($row[PR_DEFAULT_STORE]){
						$storeEntryId = $row[PR_ENTRYID];
					}
				}
				if(!$storeEntryId){
					OCP\Util::writeLog('OC_USER_KOPANO', "Cannot get message store id", 3);
					return false;
				}
				$this->kopano_store = mapi_openmsgstore($this->kopano_session, $storeEntryId);
			}
			if(!$this->kopano_store){
				OCP\Util::writeLog('OC_USER_KOPANO', "Cannot get message store!", 3);
				return false;
			}
			return true;
		}
		return false;
	}

	public function checkAdminUser(){
		if($this->getDefaultStore()){
			$info =	mapi_zarafa_getuser($this->kopano_store, $this->getUser());
			if(!$info)
				return false;
			return $info["admin"];
		}
		return false;
	}

	public function checkPassword($uid, $password){
		OCP\Util::writeLog('OC_USER_KOPANO', "checking password for $uid", 3);
		$session = mapi_logon_zarafa($uid, $password, $this->kopano_server);
		if(!$session){
			OCP\Util::writeLog('OC_USER_KOPANO', "Logon failed for '$uid'", 3);
			return false;
		}

		//get the users mailaddress and set it
		$msgStoresTable = mapi_getmsgstorestable($session);
		$msgStores = mapi_table_queryallrows($msgStoresTable, array(PR_DEFAULT_STORE, PR_ENTRYID));
		$storeEntryId = false;
		foreach($msgStores as $row){
			if($row[PR_DEFAULT_STORE]){
				$storeEntryId = $row[PR_ENTRYID];
			}
		}
		if($storeEntryId){
			$store = mapi_openmsgstore($session, $storeEntryId);
			if($store){
				$info = mapi_zarafa_getuser($store, $uid);
				if($info){
					$email = $info["emailaddress"];
					OCP\Config::setUserValue($uid, 'settings', 'email', $email);
				} else {
					OCP\Util::writeLog('OC_USER_KOPANO', "Error getting user info for $uid", 3);
				}
			}
		}
		return $uid;
        }

	public function deleteUser($uid){
		OCP\Util::writeLog('OC_USER_KOPANO', 'Not possible to delete kopano users from web frontend', 3);
		return false;
	}

	public function setPassword ( $uid, $password ) {
		OCP\Util::writeLog('OC_USER_KOPANO', 'Setting user password from web frontend using kopano user backend ist not implemented', 3);
		return false;
	}

	public function getUsers($search = '', $limit = 10, $offset = 0){
		OCP\Util::writeLog('OC_USER_KOPANO', 'getUsers: searching for:"'. $search . '"', 3);
		if(count($this->kopano_users) == 0){
			$this->kopano_users = array();
			$userList = array();
			if(!$this->getDefaultStore())
				return $this->kopano_users;
			$userList = mapi_zarafa_getuserlist($this->kopano_store);
			foreach($userList as $userName => $userData){
				if($userName == "SYSTEM")
					continue;
				array_push($this->kopano_users, $userName);
			}
		}
		$kopano_users = $this->kopano_users;
		$this->userSearch = $search;
		if(!empty($this->userSearch)) {
			$kopano_users = array_filter($kopano_users, array($this, 'userMatchesFilter'));
		}
		if($limit == -1) {
			$limit = null;
		}
		return array_slice($kopano_users, $offset, $limit);
	}

	public function userMatchesFilter($user) {
		return (strripos($user, $this->userSearch) !== false);
	}

	public function userExists($uid){
		if(!$uid)
			return false;
		$users = $this->getUsers();
		$exists = in_array($uid, $users);
		if(!$exists){
			OCP\Util::writeLog('OC_USER_KOPANO', "User '$uid' does not exist!", 3 );
			return false;
		}
		return true;
	}
}
?>
