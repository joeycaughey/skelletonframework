<?php
$data = array( 
	(object) array(
			"header" => 1,
			"name" => "User Management",
			"title" => "User Management",
	),
	(object) array(
			"url" => get_uri("module_users_admin_url"),
			"name" => "User List",
			"title" => "User List",
			"accessKey" => "1"
	),
	(object) array(
			"url" => get_uri("module_users_admin_add_url"),
			"name" => "Add User",
			"title" => "Add User",
			"accessKey" => "2"
	),
	(object) array(
			"header" => 2,
			"name" => "Group Management",
			"title" => "Group Management",
	),
	(object) array(
			"url" => get_uri("module_users_admin_groups_url"),
			"name" => "Manage Groups",
			"title" => "Manage Groups",
			"accessKey" => "1"
	),
	(object) array(
			"url" => get_uri("module_users_admin_groups_add_url"),
			"name" => "Add a Group",
			"title" => "Add a Group",
			"accessKey" => "2"
	),
);

include("modules/cms/admin/build.side_menu.php");
