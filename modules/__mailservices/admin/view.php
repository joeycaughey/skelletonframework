<?php
global $UsersModel;
global $MessagesModel;
global $ContactsModel;
global $css_files;
$css_files[] = "_templates/admin/css/".$_GET["user_type"].".css";

$UsersModel->ACTIVE_USER();


function parse($input) {
	
	return preg_replace("|http://([a-z0-9?./=%#_]{1,500})|i", '<a href="http://$1">http://$1</a>', $input);
	
}

$message = $MessagesModel->find("WHERE sent_to_user_id = '{$UsersModel->ACTIVE_USER["id"]}' and id = '{$_GET["id"]}'", false);

include("modules/mailservices/admin/_nav.php");
?>
<hr />
<p class="first"></p>
<p>From: <?= $ContactsModel->display_name($message["sent_from_user"]["contact_id"])?></p>

<p><b><?=$message["subject"]?></b>

<p><?=nl2br(parse($message["body"]))?></p>
<hr />
<?php include("modules/messaging/admin/_nav.php"); ?>