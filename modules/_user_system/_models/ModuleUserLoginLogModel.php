<?php
$ModuleUserLoginLogModel = new ModuleUserLoginLogModel($CONFIG["site"], "tbl_module_user_login_log");

class ModuleUserLoginLogModel extends Model {
	
	
	var $schema = array(
		"structure" => array(
			array("user_id", "int"),
			array("ip", "varchar", 60),
			array("date_added", "int"),
			array("date_last_modified", "int")
		),
		"index" => array(
			"user_id"
		)
	);
	
	function attempts($user_id) {
		$Logins = $this->find("WHERE user_id = '{$user_id}'", true);
		return count($Logins);
	}
	
	
	function insert($values) {
		$exists = $this->find("WHERE user_id = '{$values["user_id"]}' AND ip = '{$values["ip"]}'", false);
		
		if ($exists) {
			return parent::update($values, str2int($exists["id"]));
		}
		return parent::insert($values);
	}
}