<?PHP
global $CONFIG;
global $UsersModel;
global $UserGroupsModel;
global $UserPrivilagesModel;
global $MessagesModel;

$Groups = $UserGroupsModel->find("WHERE id=id ORDER BY name", true);


$form = new FormHelper("");
$form->legend("User Contact Information");
$form->input("contact[first_name]", array("label" => "First Name", "required" => VALID_notnull));
$form->input("contact[last_name]", array("label" => "Last Name", "required" => VALID_notnull));
$form->input("email", array("label" => "Email", "required" => VALID_notnull));
$form->input("contact[phone]", array("label" => "Phone"));
$form->input("contact[mobile]", array("label" => "Mobile"));
$form->textarea("notes", array("label" => "Notes"));

$select = '<li>';
$select.= '<label>Group Permissions</label>';
$select.= '<select name="groups[]" id="groups" multiple="true">';
foreach($Groups as $group) {
	$selected = $UserPrivilagesModel->has_access($group["name"], false, $_GET["id"]);
	$selected = ($selected) ? ' selected ' : '';
	$select.= '<option value="'.$group["id"].'" '.$selected.'>'.$UserGroupsModel->display($group["id"]).'</option>';
}
$select.= '</select>';
$select.= '</li>';

$form->html($select);

if ($_POST["password"]!="") {
	$form->password("password", array("label" => "Password", "required" => VALID_password));
} else {
	$form->password("password", array("label" => "Password"));
}
$form->buttons(
	array(
		array(
			"save-button", 
			array(
				"label" => "Save",
				"class" => "save add"
			)
		),
		array(
			"add-option", 
			array(
				"type" => "option",
				"label" => "Add another user",
				"value" => ""
			)
		)
	)
);

if ($_POST) {
	$_POST["contact"]["email"] = $_POST["email"];
	$form->data = $_POST;
	if ($form->validates()) {
		$_POST["email"] = $_POST["contact"]["email"];
		
		if ($_GET["id"]) {
			$UsersModel->update($_POST, str2int($_GET["id"])); 
		} else {
			$_POST["status"] = "Active";
			$_GET["id"] = $UsersModel->insert($_POST); 
			
			$_POST["LOGIN_URL"] = "http://".$CONFIG["host"].get_uri("home_url");
			$MessagesModel->send_to_email($_POST["email"], "nutritionist-signup-email", $_POST);
		}
			
		$UserPrivilagesModel->update_privilages($_GET["id"], $_POST["groups"]);
	
		if ($_POST["add-option"]) unset($_POST);
		else header("Location: ".get_uri("module_users_admin_list_url"));	
	}

}

if ($_GET["id"]) {
	$form->data = $UsersModel->find("WHERE id = '{$_GET["id"]}'", false);	
}


?>

     
<h2><a href="<?= get_uri("module_users_admin_list_url") ?>">&lt;&lt; Back to Users</a> | <?= ($_GET["id"]) ? 'Edit' : 'Add' ?> User</h2>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">
		Public Users are users with granted privileges to your website (ie. Clients as part of the E-Commerce module)
</p>
<?php endif; ?>

<?= $form->render() ?>



<link rel="stylesheet" type="text/css" href="/_assets/jquery.multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="/_assets/jquery.multiselect/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-darkness/jquery-ui.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/_assets/jquery.multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript" src="/_assets/jquery.multiselect/assets/prettify.js"></script>
<script type="text/javascript">
	$("select#groups").multiselect({
		multiple: true
	});
</script>



