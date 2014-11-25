<?php
global $css_files;
$css_files[] = "_templates/admin/css/".$_GET["user_type"].".css";

global $CONFIG;
global $UsersModel;
global $UserGroupsModel;
global $MessagesModel;
global $FacilitiesModel;
global $AgenciesModel;
global $FacilityUsersModel;
global $AgencyUsersModel;
global $UserAccessModel;
global $UserAccessPrivilagesModel;


$FacilitiesModel->ACTIVE_FACILITY();
$AgenciesModel->ACTIVE_AGENCY();

$form = new FormHelper("name");
$form->legend("Contact Information");
$form->input("user[contact][first_name]", array("label" => "First Name", "required" => VALID_notnull));
$form->input("user[contact][last_name]", array("label" => "Last Name", "required" => VALID_notnull));
$form->phone("user[contact][phone]", array("label" => "Phone", "required" => VALID_notnull));

$form->legend("Login Information");
if (!$_GET["id"]) {
	$form->input("user[email]", array("label" => "Email Address", "required" => VALID_useremail));
} else {
	$form->input("user[email]", array("label" => "Email Address"));
}

if ($_GET["user_type"]=="facility") {
	$form->checkboxes("permissions", array("label" => "Permissions", "options" => $UserAccessModel->options("facility")));
} else if ($_GET["user_type"]=="agency") {
	$form->checkboxes("permissions", array("label" => "Permissions", "options" => $UserAccessModel->options("agency")));
}

if ($_GET["id"]) {
	$form->button("Save User", array("label" => "&nbsp;"));
} else {
	$form->button("Add User", array("label" => "&nbsp;"));
}

if ($_POST) {
	$form->data = $_POST;
	if ($form->validates()) {

		if ($user_id = $_GET["id"]) {
			 $UsersModel->update($_POST["user"], $_GET["id"]);
		} else {	
			if ($user_id = $UsersModel->insert($_POST["user"])) {
				if ($_GET["user_type"]=="facility") {
					$group = "facility";
					$FacilityUsersModel->insert(array("user_id" => $user_id, "facility_id" => $FacilitiesModel->ACTIVE_FACILITY["id"]));			
				} else if ($_GET["user_type"]=="agency") {	
					$group = "agency";
					$AgencyUsersModel->insert(array("user_id" => $user_id, "agency_id" => $AgenciesModel->ACTIVE_AGENCY["id"]));			
				}
					
				$UsersModel->add_to_group($user_id, $group);
				
				$template = ($_GET["user_type"]=="facility") ? "facility-user-setup-email" : "agency-user-setup-email";
				
				$variables["agency"] = $AgenciesModel->display($AgenciesModel->ACTIVE_AGENCY["id"]);
				$variables["facility"] = $FacilitiesModel->display($FacilitiesModel->ACTIVE_FACILITY["id"]);
				$variables["setup_url"] = "http://".$CONFIG["host"].get_uri("admin_user_setup_url", array("user_type" => $_GET["user_type"], "hash" => $UsersModel->hash($user_id)));
				
				$MessagesModel->send($user_id, $template, $variables);
			}
		}
		
		
		if (isset($_POST["permissions"])) {
			$UserAccessPrivilagesModel->clear($user_id);
			foreach ($_POST["permissions"] as $access_id) {
				$UserAccessPrivilagesModel->insert_if_doesnt_exist((array("user_id" => $user_id, "access_id" => $access_id)), array("user_id", "access_id"));
			}
		}
		
		
		header("Location: ".get_uri("admin_users_url", array("user_type" => $_GET["user_type"])));
	}
}

if ($_GET["id"]) {
	$form->data["user"] = $UsersModel->find("WHERE id = '{$_GET["id"]}'", false);
	$form->data["permissions"] = $UserAccessPrivilagesModel->permissions($_GET["id"]);
}


?>
<h2><?= ($_GET["id"]) ? 'Edit' : 'Add' ?> User</h2>

<?php if ($_GET["id"]) : ?>
	<p>Change the user details below.</p>
<?php else : ?>
	<p>The new user will be sent an email giving them a link to update their password and information if they choose. </p>
<?php endif; ?>
<?= $form->render() ?>


    