<?php
$UserPrivilagesModel = new UserPrivilagesModel($CONFIG["site"], "tbl_module_user_privilages");

class UserPrivilagesModel extends Model {
	public $action = "limit-to";
	public $groups = array();
	
	var $schema = array(
		"structure" => array(
			array("user_id", "int"),
			array("group_id", "int"),
			array("status", "set", "'Active', 'Disabled'"),
			array("date_added", "int")
		)
	);
	
	function has_access($permission_group, $action = false, $id = false) {
		global $ACTIVE_USER;
		global $sitename;
		global $UsersModel;
		global $UserGroupsModel;

		$groups = array();
		$Groups = $UserGroupsModel->find("WHERE id = id", true);
		foreach($Groups as $group) {
			$key = $group["id"];
			$groups[$key] = $group;
		}
		
		if ($id) {
			$UserSession = $UsersModel->find("WHERE id = '{$id}'", false);
			$Permissions = $this->find("WHERE user_id = '{$id}'", true, array("group_id"));
			
			foreach($Permissions as $permission) {
				$pkey = $permission["group_id"];
				$UserSession["groups"][] = $groups[$pkey];
			}
		} else {
			$UserSession = $_SESSION[$sitename]["user"]["authentication"];
		}
		
		//print_r($UserSession);
		
		if (!is_array($UserSession["groups"])) return false;
		
		foreach($UserSession["groups"] as $group) {
			$this->groups[] = $group["name"];	
		}
		
		$user_in_group = in_array($permission_group, $this->groups);
		
		if ($action=="matches_user_id") {
			if ($user_in_group && $id) {
				if ($ACTIVE_USER["id"]==$id) return true;
			}
		} else {
			return ($user_in_group) ? true : false;
		}
		return false;
	}
	
	function update_privilages($user_id, $groups) {
		$this->delete("WHERE user_id = '{$user_id}'");
		foreach($groups as $group) {
			$this->insert(array("user_id" => $user_id, "group_id" => $group));
		}
	}
	
	function insert($values) {
		$exists = $this->find("WHERE user_id = '{$values["user_id"]}' AND group_id = '{$values["group_id"]}' ", true);
		return ($exists) ? $exists["id"] : parent::insert($values);
	}
}
/*
if (!$UserPrivilagesModel->has_access(array("affiliate_admin", "affiliate_master"))) {
	header("Location: ".get_uri("login_url"));
}
*/