<?php 
global $ContactsModel;
global $ImagesModel;
global $UsersModel;
global $UserProfileModel;

$UsersModel->ACTIVE_USER();

?>
<table class="standard" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr class="header">
		<td>&nbsp;</td>
		<td>Details</td>
		<td>Subject / Message</td>
	</tr>
	<?php if (count($Messages)==0) : ?>
	<tr>
		<td colspan="3">You currently have no messages.</td>
	</tr>
	<?php else :?>
		<?php foreach($Messages as $message) : ?>
		<tr>
			<td><input type="checkbox" name="ids[]" value="<?=$message["id"]?>" /> &nbsp; &nbsp;</td>
			<td>
				<b>
					<?=$ContactsModel->display_name($message["sent_to_user"]["contact_id"])?>
				</b>
				<br />
				<span><?= date("F d @ H:ia", $message["date_added"])?></span>
			</td>
			<td>
				<b><a href="<?= get_uri("messaging_view_url", array("user_type" => $_GET["user_type"], "id" => $message["id"])) ?>"><?= parse_content(truncate($message["subject"], 50))?></a></b><br />
				<span><?= parse_content(truncate($message["body"], 100))?></span>
			</td>
		</tr>
		<?php endforeach; ?>
	<?php endif; ?>
</table>