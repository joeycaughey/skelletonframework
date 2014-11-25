<?php
$UserGroupsModel = new UserGroupsModel($CONFIG["site"], "tbl_module_user_groups");

class UserGroupsModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("name", "varchar", 60),
			array("description", "varchar", 255),
			array("login_url", "varchar", 255),
			array("date_added", "int"),
			array("order_id", "int")
		)
	);
	
	function user_in_group($user_id, $group, $message = false) {
		global $UsersModel;
		global $UserPrivilagesModel;

		$User = $UsersModel->find(str2int($user_id));
	}
	
}