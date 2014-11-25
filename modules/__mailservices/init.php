<?php
global $modules;
global $widgets;

$module_name = "mailinglists";
if ($debug) echo $module_name." Init Loaded";

// Enter the modules primary navigation information
$modules[] = 
	(object) array(
		"url" =>  get_uri("admin_module_mailinglists_url"),
		"name" => $module_name,
		"code_name" => "mailinglists",
		"title" => "Return to your dashboard",	
		"fullname" => "Emails, Mailing Lists, Contacts",							
		"description" => "Update all your emails, mailinglists, newsletters and contacts here.",
		"class" => "mod_mailinglists",
		"hideWelcome" => "0",
		"accessKey" => "E",
		"features" => array("navigation" => true, "user" => true)
		
	);
	
$widgets[] = array("name" => "News Widget", "source" => "modules/news/frontend/widget.php");


