<?php
$UsersModel = new UsersModel($CONFIG["site"], "tbl_module_users");
//$UsersModel->model_has_one("address", "AddressesModel", "address_id");
$UsersModel->model_has_one("contact", "ContactsModel", "contact_id");
$UsersModel->ACTIVE_SESSION();

class UsersModel extends Model {
	public $groups;
	public $login_url;
	public $ACTIVE_USER;
	public $ACTIVE_SESSION;
	
	var $schema = array(
		"structure" => array(
			array("contact_id", "int"),
			array("address_id", "int"),
			array("email", "varchar", "128"),
			array("password", "varchar", "255"),
			array("password_hash", "varchar", "255"),
			array("hash", "varchar", "255"),
			array("ipaddress", "varchar", "30"),
			array("data", "longtext"),
			array("status", "set", "'Active', 'Disabled', 'Unverified', 'Inactive'", "Unverified"),
			array("last_login", "int"),
			array("date_added", "int")
		),
		"index" => array(
			"contact_id",
			"address_id"
		)
	);
	
	
	function ACTIVE_USER() {
		global $sitename;
		$this->ACTIVE_USER = $this->find("WHERE id = '{$_SESSION[$sitename]["user"]["authentication"]["id"]}'");

		return ($_SESSION[$sitename]["user"]["authentication"]["authenticated"]) ? $_SESSION[$sitename]["user"]["authentication"] : false;	
	}
	
	
	function ACTIVE_SESSION() {
		global $sitename;
		
		if ($this->ACTIVE_USER()) {
			$this->ACTIVE_SESSION = $this->ACTIVE_USER;
			return true;
		}
		
		$this->ACTIVE_SESSION["ipaddress"] = $_SERVER["REMOTE_ADDR"];
		$this->ACTIVE_SESSION["hash"] =  ($_COOKIE[$sitename]["hash"]) ? $_COOKIE[$sitename]["hash"] : md5($this->ACTIVE_SESSION["ipaddress"]);
		
		if (!$_COOKIE[$sitename]["hash"]) 
			setcookie("{$sitename}[hash]", $this->ACTIVE_SESSION["hash"]);
			
		if ($ACTIVE_SESSION = $this->find("WHERE hash = '{$this->ACTIVE_SESSION["hash"]}'", false)) {
			$ACTIVE_SESSION["data"] = unserialize($ACTIVE_SESSION["data"]);
			$this->ACTIVE_SESSION = $ACTIVE_SESSION;
		} else {
			$this->ACTIVE_SESSION["data"] = serialize($this->ACTIVE_SESSION["data"]);
			parent::insert($this->ACTIVE_SESSION);
		}
		return true;
	}
	
	function UPDATE_SESSION($ACTIVE_SESSION) {
		global $ContactsModel;
		
		$ACTIVE_SESSION["data"] = serialize($ACTIVE_SESSION["data"]);
		if (!$ACTIVE_SESSION["contact_id"]) {
			$ACTIVE_SESSION["contact_id"] = $ContactsModel->insert($ACTIVE_SESSION["contact"]);	
		}
		
		$this->update($ACTIVE_SESSION, "WHERE id = '{$ACTIVE_SESSION["id"]}'");
	}
	
	function hash($user_id = false) {
		$hash = $this->find("WHERE id = '{$user_id}'", false, array("hash"));
		return $hash["hash"];
	}
	
	function get_from_hash($hash) {
		return $this->find("WHERE hash = '{$hash}'", false);
	}
	
	function validate_from_hash($hash) {
		global $sitename;
		
		$User = $this->find("WHERE hash='{$hash}'", false, array("id", "password", "last_login"));

		if ($User) {
			$_SESSION[$sitename]["user"]["authentication"] = array(
				"authenticated" => true, 
				"id" => $User["id"], 
				"groups" => $this->groups($User["id"]),  
				"last_login" => $User["last_login"]
			);

			$v["status"] = "Active";
			$v["last_login"] = TODAYSDATE;
			$this->update($v, "WHERE id = '{$User["id"]}'");
			return true;
		}
		return false;
	}
	
	function validate($email, $password) {
		global $sitename;
		
		$User = $this->find("WHERE email = '".$email."'", false, array("id", "password"));

		if ($User) {
			if ($User["password"]==md5($password) || $User["password"]==$password) {
				
				$_SESSION[$sitename]["user"]["authentication"] = array(
					"authenticated" => true, 
					"id" => $User["id"], 
					"groups" => $this->groups($User["id"]),  
					"last_login" => $User["last_login"]
				);

				$v["last_login"] = TODAYSDATE;
				$this->update($v, "WHERE id = '{$User["id"]}'");
				return true;
			} else {
				feedback("errors", "Invalid password.");
			}
		} else {
			feedback("errors", "User not found.");
		}
		return false;
	}
	
	function login($email, $password, $remember_me = false) {
		global $sitename; 
		
		$User = $this->find("WHERE email = '".$email."'", false, array("id", "password", "status"));
		
		if ($this->ACTIVE_SESSION() && $email!="admin") {
			if ($User["hash"]!=$this->ACTIVE_SESSION["hash"] && (!$User["email"] && !$this->ACTIVE_SESSION["email"])) {
				$this->single_update("hash", $this->ACTIVE_SESSION["hash"], "WHERE id = '{$User["id"]}'");
				$this->delete("WHERE id = '{$this->ACTIVE_SESSION["id"]}'");
			}
		}
		
		$this->update(array("last_login" => TODAYSDATE), "WHERE id = '{$User["id"]}'");
		if ($this->validate($email, $password) && $User["status"]=="Active") {
			header("Location: ".$this->login_url($User["id"]));
		} 
		return false;
	}
	
	function groups($user_id) {
		global $UserGroupsModel;
		global $UserPrivilagesModel;

		$UserPrivilages = $UserPrivilagesModel->find("WHERE user_id = '".$user_id."'", true);
		foreach($UserPrivilages as $up) {
			$this->groups[] = $UserGroupsModel->find("WHERE id = '".$up["group_id"]."'", false);	
		}
		return $this->groups;
	}
	
	function add_to_group($user_id, $group) {
		global $CONFIG;
		global $UserGroupsModel;
		global $UserPrivilagesModel;
		
		$Group = $UserGroupsModel->find("WHERE name = '{$group}'", false);
		
		if (!$Group) return false;

		$v["user_id"] = $user_id;
		$v["group_id"] = $Group["id"];
		$UserPrivilagesModel->insert_if_doesnt_exist(array("user_id" => $user_id, "group_id" => $Group["id"]), array("user_id", "group_id"));
	}

	function in_group($user, $group) {
                /*global $CONFIG;
		global $UserGroupsModel;
		global $UserPrivilagesModel;
		$champ = "group_id" ;
		$table="tbl_module_user_privilages";
		$condition="WHERE user_id = '".$user."'" ;
		if( strtolower((mySQL_select_precise($this->OBJ,$champ, $table, $condition )) == strtolower($group)) ;
			return true;	
		else
			return false;*/
	}
	
	
	function login_url($user_id) {
		global $UserGroupsModel;
		global $UserPrivilagesModel;
		
		$groups = array();
		
		$UserPrivilages = $UserPrivilagesModel->find("WHERE user_id = '".$user_id."'", true);
	
		foreach($UserPrivilages as $up) {
			$groups[] =  $up["group_id"];
		}
		
		if (count($groups)>0) {
			$Group = $UserGroupsModel->find("WHERE id IN (".implode(",", $groups).") ORDER BY order_id", false);	
			return $Group["login_url"];
		}
		return false;
	}
	
	function update_last_login($id) { 
		global $CONFIG;
		$v["last_updated"] = TODAYSDATE;
		$this->update($v, "WHERE id = '{$id}'");
	}	
	
	function last_active($user_id = false) {
		$this->ACTIVE_USER();
		$user_id = ($user_id) ? $user_id : $this->ACTIVE_USER["id"];
		
		$user = $this->find("WHERE id = '{$user_id}'", false, array("last_login"));	
	
		return str_replace("-", "", FORMAT_date_ago($user["last_login"]))." ago";
	}
	
	function parent_insert($values = array()) {
		return parent::insert($values);	
	}

	
	function insert($values = array()) {
		global $CONFIG;
		global $MessagesModel;
		
		if ($values["contact"]["email"] && !$values["email"]) {
			$values["email"] = $values["contact"]["email"];
		}

		if ($this->ACTIVE_SESSION() && !$this->ACTIVE_USER()) {
			$this->update($values, "WHERE id = '{$this->ACTIVE_SESSION["id"]}'");	
			$user_id = $this->ACTIVE_SESSION["id"];
		} else {	
			$values["hash"] = md5($_SERVER["REMOTE_ADDR"]);
			$user_id = parent::insert($values);
		}
		
		$this->add_to_group($user_id, "user");

		return $user_id;
	}
	
	function update($values, $sql) {
		parent::update($values, $sql);
	}
	
	function display($user_id) {
		$user = $this->find("WHERE id = '{$user_id}'", false);	
		return $user["contact"]["first_name"]." ". $user["contact"]["last_name"];
	}
}