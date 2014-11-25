<?php
$UserAccessModel = new UserAccessModel($CONFIG["site"], "tbl_module_user_access");

class UserAccessModel extends Model {
	
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

	function options($group) {
		global $UserGroupsModel;
		
		$Group = $UserGroupsModel->find("WHERE name = '{$group}'", false, array("id"));

		return parent::options("id", "description", array("sql" => "WHERE group_id = '{$Group["id"]}'"));
	}
	
}
