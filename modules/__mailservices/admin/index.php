<?php
global $UsersModel;
global $MessagesModel;
global $css_files;
$css_files[] = "_templates/admin/css/".$_GET["user_type"].".css";

$UsersModel->ACTIVE_USER();

if ($_POST) {
	if (count($_POST["ids"])>0) {
		foreach($_POST["ids"] as $id)  {
			if ($_POST["op"]=="delete") $MessagesModel->delete("WHERE id = '{$id}'");
		}
	}
}

if ($_GET["type"]=="sent") {
	$Messages = $MessagesModel->find("WHERE sent_by_user_id = '{$UsersModel->ACTIVE_USER["id"]}' and parent_id = 0", true);
} else {
	$Messages = $MessagesModel->find("WHERE sent_to_user_id = '{$UsersModel->ACTIVE_USER["id"]}' and parent_id = 0", true);
}

include("modules/mailservices/admin/_nav.php");
?>

<form method="POST">
	<?php include("modules/mailservices/admin/_messages.php"); ?>
	<br />
	<select name="op">
		<option value="null">-- With Selected --</option>
		<option value="delete">Delete</option>
	</select>
	<button type="submit">Go</button>
</form>
