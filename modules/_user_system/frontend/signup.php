<?php 
global $CONFIG;
global $UsersModel;
global $MessagesModel;
global $UsersModel;

$form = new FormHelper("");
$form->legend("Input Your Personal Information");
$form->input("user[contact][first_name]", array("label" => "First Name", "required" => VALID_notnull));
$form->input("user[contact][last_name]", array("label" => "Last Name", "required" => VALID_notnull));
$form->input("user[contact][email]", array("label" => "Email", "required" => VALID_useremail));
$form->password("user[password]", array("label" => "Password", "required" => VALID_password));
//$form->date("birthdate", array("label" => "Date of Birth"));
$form->button("Next Step", array("class" => "arrow"));


if ($_POST) {
	$form->data = $_POST;
	if ($form->validates()) {
		if ($UsersModel->ACTIVE_USER()) {
			$UsersModel->update($_POST, $UsersModel->ACTIVE_USER["id"]);			
		} else {
			$user_id = $UsersModel->insert($_POST);
			$UsersModel->validate($_POST["user"]["contact"]["email"], $_POST["user"]["password"]);
			header("Location: ".get_uri("measurements_url"));
		}
	}
}

?>
<h2>Signup to get the most out of Dwellopolis:</h2>

<div class="two-column-offset-left-layout">
	<div class="column first">
		<h2 >&nbsp;</h2>
		<ol class="steps">
			<li class="on">1. Create an Account</li>
			<li>2. Link Social Media</li>
			<li>3. Complete Profile</li>
			<li>4. Find Properties</li>
		</ol>
	</div>
	<div class="column">		
		<?php $form->render(); ?>
	</div>
	<div style="clear: both;"></div>
</div>
