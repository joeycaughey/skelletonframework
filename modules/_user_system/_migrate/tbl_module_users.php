<?PHP
global $CONFIG;
global $UsersModel;

DBQUERY($CONFIG["site"], "TRUNCATE {$UsersModel->table};");

$UsersModel->parent_insert(array(
		"contact_id" => 1,
		"email" => "admin", 
		"password" => "nimda",
		"status"	=> "Active"
	));	

$UsersModel->parent_insert(array(
		"contact_id" => 2,
		"email" => "joey.caughey@gmail.com", 
		"password" => "testing",
		"status"	=> "Active"
	));	
	

	

	