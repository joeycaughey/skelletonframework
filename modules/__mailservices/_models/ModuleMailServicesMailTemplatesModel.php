<?php
$ModuleMailServicesEmailTemplatesModel = new ModuleMailServicesEmailTemplatesModel($CONFIG["site"], "tbl_module_mailservices_email_templates");

class ModuleMailServicesEmailTemplatesModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("template_id", "varchar", 50),
			array("from", "varchar", 255),
			array("from_name", "varchar", 50),
			array("subject", "varchar", 255),
			array("body", "longtext"),
			array("date_added", "bigint")
		)
	);
	
}