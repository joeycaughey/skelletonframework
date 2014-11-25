<?php
$UserAccessPrivilagesModel = new UserAccessPrivilagesModel($CONFIG["site"], "tbl_module_user_access_privilages");

class UserAccessPrivilagesModel extends Model {

	var $schema = array(
		"structure" => array(
			array("group_id", "int"),
			array("code", "varchar", "100"),
			array("description", "varchar", "255"),
			array("status", "set", "'Active', 'Disabled', 'Unverified', 'Inactive'"),
			array("date_added", "int")
		),
		"index" => array(
			"group_id"
		)
	);
	
	function permissions($user_id = false) {
		global $UsersModel;
		
		$user_id = ($user_id) ? $user_id : $UsersModel->ACTIVE_USER["id"]; 
		
		$ids = array();
		foreach($this->find("WHERE user_id = '{$user_id}'", true, array("access_id")) as $P) {
			$ids[] = $P["access_id"];
		}
		return $ids;	
	}
	
	function has_access($user_id = false, $code) {
		global $UsersModel;
		global $UserAccessModel;
		
		$user_id = ($user_id) ? $user_id : $UsersModel->ACTIVE_USER["id"]; 
		
		$UA = $UserAccessModel->find("WHERE code = '{$code}'", false, array("id"));
		
		$P = $this->find("WHERE user_id = '{$user_id}' AND access_id = '{$UA["id"]}' AND status = 'Active'", false, array("id"));
		return ($P) ? true : false;
	}
	
	function clear($user_id) {
		$this->delete("WHERE user_id = '{$user_id}'");
		return true;
	}
}
