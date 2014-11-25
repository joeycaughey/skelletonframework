<?php
global $modules;
global $widgets;

$module_name = "blog";
if ($debug) echo $module_name." Init Loaded";

// Enter the modules primary navigation information
$modules[] = 
	(object) array(
		"url" =>  get_uri("module_blog_cms_url"),
		"name" => $module_name,
		"code_name" => "blog",
		"title" => "Return to your dashboard",	
		"fullname" => "Blog",							
		"description" => "Manage your Blog.",
		"class" => "mod_blog",
		"accessKey" => "E",
		"features" => array("navigation" => true, "user" => true)
);

$widgets["widget_blog"] = array(
	"key" => "_blog",
	"name" => "Blog / Article List",
	"url" => get_uri("module_blog_url")
);
	


