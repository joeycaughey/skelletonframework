<?php
$ModuleStoreCategoriesModel = new ModuleStoreCategoriesModel($CONFIG["site"], "tbl_module_store_categories");
$ModuleStoreCategoriesModel->has_many("products", "tbl_module_store_products", "category_id");

class ModuleStoreCategoriesModel extends Model {
	
	
	var $schema = array(
	   "structure" => array(
			array("name", "varchar", 100),
			array("description", "text"),
			array("date_added", "bigint"),
			array("order_id", "bigint")
	   )
	
	);
	
	
}

