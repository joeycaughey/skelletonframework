<?php
$ModuleMailingListsModel = new ModuleMailingListsModel($CONFIG["site"], "tbl_module_mailinglists");

class ModuleMailingListsModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("title", "varchar", 100),
			array("type", "set", "'List', 'Database'"),
			array("hostname", "varchar", 255),
			array("username", "varchar", 255),
			array("password", "varchar", 255),
			array("date_added", "bigint")
		)
	);
	
	function contacts($id) {
		global $ModuleMailingListsContactsModel;
		$Contacts = $ModuleMailingListsContactsModel->find("WHERE mailinglist_id = '$id'", true, array("id"));
		return count($Contacts);
	}

	function delete($id) {
		global $ModuleMailingListsContactsModel;
		$ModuleMailingListsContactsModel->delete("WHERE mailinglist_id = '{$id}'");
		parent::delete(str2int($id));
		
	}
}

