<?php
/********** SKELETON FRONT END ROUTES *********/
$routes->set_template("_templates/frontend/index.tpl");  // Define template								


/********** SKELETON ADMIN ROUTES *********/
$routes->set_template("modules/cms/_templates/default/index.tpl");  // Define template

$routes->add_route("module_store_cms_url", "/cms/store/", "modules/store/cms/index");

$routes->add_route("module_store_cms_category_add_url", "/cms/store/category/add/:category_id/", "modules/store/cms/category/modify", array("category_id" => "[0-9]+"));
$routes->add_route("module_store_cms_category_edit_url", "/cms/store/category/edit/:id/", "modules/store/cms/category/modify", array("id" => "[0-9]+"));
$routes->add_route("module_store_cms_category_delete_url", "/cms/store/category/delete/:id/", "modules/store/cms/category/delete", array("id" => "[0-9]+"));


$routes->add_route("module_store_cms_products_delete_url", "/cms/store/delete/:id/", "modules/store/cms/delete", array("id" => "[0-9]+"));
$routes->add_route("module_store_cms_products_edit_url", "/cms/store/edit/:id/", "modules/store/cms/modify", array("id" => "[0-9]+"));
$routes->add_route("module_store_cms_products_add_url", "/cms/store/add/:group_id/", "modules/store/cms/modify", array("group_id" => "[0-9]+"));


$routes->set_template("_templates/blank.tpl");
$routes->add_route("module_store_cms_product_section_url", "/store/product/section/", "modules/store/frontend/_section");
