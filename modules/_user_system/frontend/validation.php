<?php
global $UsersModel;
global $sitename;

$User = $UsersModel->get_from_hash($_GET["hash"]);

if ($User["status"]=="Unverified") {
	$UsersModel->single_update("status", "Active", "WHERE id = '{$User["id"]}'");
	$UsersModel->validate($User["email"], $User["password"]);
	
	$_SESSION[$sitename]["first_time"] = true;
	
	if (!$User["password"] || $User["password"]=="") {
		header("Location: ".get_uri("change_password_url"));
	} else {
		header("Location: ".get_uri("home_url"));
	}
} 

$UsersModel->validate_from_hash($_GET["hash"]);


if (!$User["password"]) {
	header("Location: ".get_uri("change_password_url"));
} else {
	header("Location: ".$UsersModel->login_url($User["id"]));
}



?>

<h2>Validation</h2>
<p>The email address <?=$User["email"]?> has already been verified.</p>
