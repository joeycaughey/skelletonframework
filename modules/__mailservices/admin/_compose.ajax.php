<?php
global $UsersModel;
global $ContactsModel;
global $AgenciesModel;
global $AcencyEmployeesModel;
global $AgencyFacilitiesModel;

$UsersModel->ACTIVE_USER();
$AgenciesModel->ACTIVE_AGENCY();

if ($_POST["user_type"]=="professional") {
	$Contacts = $AcencyEmployeesModel->find("WHERE user_id = '{$UsersModel->ACTIVE_USER["id"]}' AND status = 'Active'", true, array("agency_id"));
} else if ($_POST["user_type"]=="agency") {
	$Contacts = $AcencyEmployeesModel->find("WHERE agency_id = '{$AgenciesModel->ACTIVE_AGENCY["id"]}' AND status = 'Active'", true, array("user_id"));
} else if ($_POST["user_type"]=="facility") {
	$Contacts = $AgencyFacilitiesModel->find("WHERE agency_id = '{$AgenciesModel->ACTIVE_AGENCY["id"]}' AND status = 'Active'", true, array("facility_id"));
}

?>
<div style="margin-top: -10px;">
<h2 class="left">Compose<span> Message&nbsp;</span></h2>
<form method="POST" action="<?=get_uri("messaging_url", array("user_type" => $_GET["user_type"]))?> ">
	<fieldset>
		<ol style="width: 100%;">
			<li>
				<label for="sent_to_user_id">To</label>
				<select name="sent_to_user_id">
					<?php foreach($Friends as $friend) : ?>
					<option value="<?=$friend["id"]?>" <?= ($_GET["id"]==$friend["id"]) ? 'selected' : ''?>><?=$ContactsModel->display_name($friend["contact_id"])?></option>
					<?php endforeach; ?>
				</select>
			</li>
			<li>
				<label for="subject">Subject</label>
				<input type="text" name="subject" value="" />
			</li>
			<li>
				<label for="body">Message</label>
				<textarea name="body" style="width: 70%; height: 70px;"></textarea>
			</li>
			
			<li>
				<label></label>
				<div class="button_holder">
					<button type="submit">Send</button>
					<button onclick="popup_close();" type="button">Cancel</button>
				</div>
			</li>
		</ol>
	</fieldset>
</form>
</div>