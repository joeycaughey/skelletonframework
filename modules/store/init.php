<?php
global $modules;
global $CONFIG;

$CONFIG["module"]["store"]["pricing"] = "CAD";

$module_name = "store";
if ($debug) echo $module_name." Init Loaded";

// Enter the modules primary navigation information
$modules[] = 
	(object) array(
		"url" =>  get_uri("module_store_cms_url"),
		"name" => $module_name,
		"code_name" => "store",
		"title" => "Return to your dashboard",	
		"fullname" => "Store",							
		"description" => "Store Module.",
		"class" => "mod_store",
		"hideWelcome" => "0",
		"accessKey" => "E",
		"features" => array("navigation" => true, "user" => true)
	);

$widgets["widget_store"] = array(
    "key" => "_store",
    "name" => "Store",
    "options" => array(
        array("name" => "Default", "path" => "modules/store/frontend/index.php")
    )
);