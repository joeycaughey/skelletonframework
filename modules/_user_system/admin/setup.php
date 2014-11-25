<?php
global $UsersModel;

$UsersModel->validate_from_hash($_GET["hash"]);
$UsersModel->ACTIVE_USER();

global $css_files;
$css_files[] = "_templates/admin/css/".$_GET["user_type"].".css";

if ($_GET["user_type"]=="facility") {
} else if ($_GET["user_type"]=="agency")  {
}

$form = new FormHelper("");
$form->legend("Choose your password");
$form->password("password", array("label" => "Set Password", "required" => VALID_password));
$form->legend("Enter your address information");
$form->address("address", array("label" => "Address"));
$form->button("Submit", array("label" => "&nbsp;"));


if ($_POST) {
	$form->data = $_POST;
	if ($form->validates()) {	
		$UsersModel->update($_POST, $UsersModel->ACTIVE_USER["id"]);
		feedback("notices", "Profile saved.");
		header("Location: ".get_uri("admin_url", array("user_type" => $_GET["user_type"])));
	}
}
?>
<h2>Complete User Setup</h2>
<p>A user has been created for you on ONN.com.  Complete the information below
to complete your profile.</p>

<?= $form->render() ?>
