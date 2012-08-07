<?php

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
		$this->zarafa_server = OC_Appconfig::getValue('zarafa', 'zarafa_server', OC_APP_ZARAFA_DEFAULT_SERVER);
		$this->zarafa_user_name = OC_Appconfig::getValue('zarafa', 'zarafa_user_name', OC_APP_ZARAFA_DEFAULT_USER_NAME);
		$this->zarafa_user_pass = OC_Appconfig::getValue('zarafa', 'zarafa_user_pass', OC_APP_ZARAFA_DEFAULT_USER_PASS);
	}

	private function getZarafaSession(){
		$this->zarafa_session = mapi_logon_zarafa($this->zarafa_user_name, $this->zarafa_user_pass, $this->zarafa_server, NULL, NULL);
		if($this->zarafa_session)
			return true;
		OC_Log::write('OC_USER_ZARAFA', "Cannot get zarafa session", 3);
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
					OC_Log::write('OC_USER_ZARAFA', "Cannot get message store id", 3);
					return false;
				}
				$this->zarafa_store = mapi_openmsgstore($this->zarafa_session, $storeEntryId);
			}
			if(!$this->zarafa_store){
				OC_Log::write('OC_USER_ZARAFA', "Cannot get message store with id ($storeEntryId)", 3);
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
			OC_Log::write('OC_USER_ZARAFA', "Logon failed for '$uid'", 3);
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
					\OCP\Config::setUserValue($uid, 'settings', 'email', $email);
				} else {
					OC_Log::write('OC_USER_ZARAFA', "Error getting user info for $uid", 3);
				}
			}
		}
		return $uid;
        }

	public function deleteUser(){
		OC_Log::write('OC_USER_ZARAFA', 'Not possible to delete zarafa users from web frontend', 3);
		return false;
	}

	public function setPassword ( $uid, $password ) {
		OC_Log::write('OC_USER_ZARAFA', 'Setting user password from web frontend using zarafa user backend ist not implemented', 3);
		return false;
	}

	public function getUsers(){
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
		return $this->zarafa_users;
	}

	public function userExists($uid){
		$users = $this->getUsers();
		$exists = in_array($uid, $users);
		if(!$exists){
			OC_Log::write('OC_USER_ZARAFA', "User '$uid' does not exist!", 3 );
			return false;
		}
		return true;
	}
}
?>
