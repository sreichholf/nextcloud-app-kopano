OS<?php

/**
 * ownCloud - zarafa
 */

include("mapi/mapi.util.php");
include("mapi/mapicode.php");
include("mapi/mapidefs.php");
include("mapi/mapitags.php");

class OC_USER_ZARAFA extends OC_User_Backend {
	protected $zarafa_session;
	protected $zarafa_store;
	protected $zarafa_server;
	protected $zarafa_users;
	protected $zarafa_user_name;
	protected $zarafa_user_pass;

	function __construct() {
		$this->zarafa_server = OCP\Config::getAppValue('zarafa', 'zarafa_server', OC_APP_ZARAFA_DEFAULT_SERVER);
		$this->zarafa_user_name = OCP\Config::getAppValue('zarafa', 'zarafa_user_name', OC_APP_ZARAFA_DEFAULT_USER_NAME);
		$this->zarafa_user_pass = OCP\Config::getAppValue('zarafa', 'zarafa_user_pass', OC_APP_ZARAFA_DEFAULT_USER_PASS);
	}

	private function getZarafaSession(){
		$this->zarafa_session = mapi_logon_zarafa($this->zarafa_user_name, $this->zarafa_user_pass, $this->zarafa_server, NULL, NULL);
		if($this->zarafa_session)
			return true;
		OCP\Util::writeLog('OC_USER_ZARAFA', "Cannot get zarafa session", 3);
		return false;
	}

	private function getDefaultStore(){
		if(!$this->zarafa_store){
			if($this->getZarafaSession()){	
				$msgStoresTable = mapi_getmsgstorestable($this->zarafa_session);
				$msgStores = mapi_table_queryallrows($msgStoresTable, array(PR_DEFAULT_STORE, PR_ENTRYID));
				$storeEntryId = false;
				foreach($msgStores as $row){
					if($row[PR_DEFAULT_STORE]){
						$storeEntryId = $row[PR_ENTRYID];
					}
				}
				if(!$storeEntryId){
					OCP\Util::writeLog('OC_USER_ZARAFA', "Cannot get message store id", 3);
					return false;
				}
				$this->zarafa_store = mapi_openmsgstore($this->zarafa_session, $storeEntryId);
			}
			if(!$this->zarafa_store){
				OCP\Util::writeLog('OC_USER_ZARAFA', "Cannot get message store with id ($storeEntryId)", 3);
				return false;
			}
			return true;
		}
		return false;
	}

	public function checkAdminUser(){
		if($this->getDefaultStore()){
			$info =	mapi_zarafa_getuser($this->zarafa_store, $this->getUser());
			if(!$info)
				return false;
			return $info["admin"];
		}
		return false;
	}

	public function checkPassword($uid, $password){
		$session = mapi_logon_zarafa($uid, $password);
		if(!$session){
			OCP\Util::writeLog('OC_USER_ZARAFA', "Logon failed for '$uid'", 3);
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
					OCP\Util::writeLog('OC_USER_ZARAFA', "Error getting user info for $uid", 3);
				}
			}
		}
		return $uid;
        }

	public function deleteUser($uid){
		OCP\Util::writeLog('OC_USER_ZARAFA', 'Not possible to delete zarafa users from web frontend', 3);
		return false;
	}

	public function setPassword ( $uid, $password ) {
		OCP\Util::writeLog('OC_USER_ZARAFA', 'Setting user password from web frontend using zarafa user backend ist not implemented', 3);
		return false;
	}

	public function getUsers($search = '', $limit = 10, $offset = 0){
		if(count($this->zarafa_users) == 0){
			$this->zarafa_users = array();
			$userList = array();
			if(!$this->getDefaultStore())
				return $this->zarafa_users;
			$userList = mapi_zarafa_getuserlist($this->zarafa_store);
			foreach($userList as $userName => $userData){
				if($userName == "SYSTEM")
					continue;
				array_push($this->zarafa_users, $userName);
			}
		}
		$zarafa_users = $this->zarafa_users;
		$this->userSearch = $search;
		if(!empty($this->userSearch)) {
			$zarafa_users = array_filter($zarafa_users, array($this, 'userMatchesFilter'));
		}
		if($limit == -1) {
			$limit = null;
		}
		return array_slice($zarafa_users, $offset, $limit);
	}

	public function userMatchesFilter($user) {
		return (strripos($user, $this->userSearch) !== false);
	}

	public function userExists($uid){
		$users = $this->getUsers();
		$exists = in_array($uid, $users);
		if(!$exists){
			OCP\Util::writeLog('OC_USER_ZARAFA', "User '$uid' does not exist!", 3 );
			return false;
		}
		return true;
	}
}
?>
