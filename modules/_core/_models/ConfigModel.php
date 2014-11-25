<?php
$ConfigModel = new ConfigModel($CONFIG["site"], "tbl_core_config");


class ConfigModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("name", "varchar", 255),
			array("value", "text"),
			array("data", "text")
		)
	);
	
}
