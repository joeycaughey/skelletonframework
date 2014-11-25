<?php
$ModuleStoreProductsModel = new ModuleStoreProductsModel($CONFIG["site"], "tbl_module_store_products");

class ModuleStoreProductsModel extends Model {
	var $resource = "module_store_products";
	
	var $schema = array(
	   "structure" => array(
			array("image_id", "int"),
			array("category_id", "int"),
			array("production_id", "int"),
			array("name", "varchar", 100),
			array("description", "text"),
			array("price", "decimal", "11,2"),
			array("sale_price", "decimal", "11,2"),
			array("shipping", "decimal", "11,2"),
			array("handling", "decimal", "11,2"),
			array("date_added", "bigint"),
			array("order_id", "bigint"),
				
	   ),
	   "index" => array(
	       "category_id",
	       "production_id"
	   )
	
	);
	
}

