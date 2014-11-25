<?php
$ModuleMailServicesNewslettersModel = new ModuleMailServicesNewslettersModel($CONFIG["site"], "tbl_module_mailservices_newsletters");

class ModuleMailServicesNewslettersModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("file_id", "int", 11),
			array("thumb_id", "int", 11),
			array("title", "varchar", 100),
			array("date_added", "bigint"),
			array("views", "bigint")
		)
	);
	
	
	function most_popular() {
		return $this->find("WHERE id = id ORDER BY views", true);	
	}
	
	function viewed($id) {
		$Post = $this->find("WHERE id = '{$id}'", false, array("views"));	
		
		$v["views"] = $Post["views"]++;
		$this->update($v, $id);
	}
	
}