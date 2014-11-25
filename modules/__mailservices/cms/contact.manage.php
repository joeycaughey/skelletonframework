<?PHP
global $CONFIG;
global $ModuleMailingListsModel;
global $ModuleMailingListsContactsModel;

$MailingList = $ModuleMailingListsModel->find_by_slug("title", $_GET["mailinglist"]);

if ($_POST) {
	$VALID = array();
	$VALID["email"] = validate(VALID_email($_POST["contact"]["email"]));

	if (isnotnull($VALID)) {
		$_POST["mailinglist_id"] = $MailingList["id"];
		if ($_GET["id"]) {
			$success = $ModuleMailingListsContactsModel->update($_POST, $_GET["id"]);
		} else {
			$success = $ModuleMailingListsContactsModel->insert($_POST);
		}
		
		if ($success) {
			if ($_POST["return"]==2) header("Location: ".get_uri("admin_module_mailinglists_contacts_url", array("mailinglist" => FORMAT_forurl($MailingList["title"]))));
			unset($_POST);
		}
	}
	
	$form = $_POST;
}


if ($_GET["id"]) {
	$form = $ModuleMailingListsContactsModel->find(str2int($_GET["id"]));
} 

?>

<h2>Add a New Contact | <?=$MailingList["title"]?></h2>
<?= display_feedback() ?>
<?php if (config("editMode") == "wizard"):?>
    <p class="message info">
        Please provide inforamation regarding this mailing list.
    </p>
<?php endif; ?>

<form  method="post" enctype="multipart/form-data">
	<fieldset>
	<legend>Contact information</legend>
            <ol>            
		<li>
			<label for="contact[first_name]">First Name</label>
			<input type="text" name="contact[first_name]" value="<?= $form["contact"]["first_name"]?>" id="first_name" size="30" class="required">
		</li>
		<li>
			<label for="contact[last_name]">Last Name</label>
			<input type="text" name="contact[last_name]" value="<?= $form["contact"]["last_name"]?>" id="first_name" size="30" class="required">
		</li>
		<li>
			<label for="contact[email]">Email</label>
			<input type="text" name="contact[email]" value="<?= $form["contact"]["email"]?>" id="first_name" size="30" class="required">
		</li>
		<li>
			<label for="contact[phone]">Phone</label>
			<input type="text" name="contact[phone]" value="<?= $form["contact"]["phone"]?>" id="first_name" size="30">
		</li>
		<li>
			<label for="contact[mobile]">Mobile</label>
			<input type="text" name="contact[mobile]" value="<?= $form["contact"]["mobile"]?>" id="first_name" size="30">
		</li>
		<li>
			<label for="contact[fax]">Fax</label>
			<input type="text" name="contact[fax]" value="<?= $form["contact"]["fax"]?>" id="first_name" size="30">
		</li>
	
		<li>
			<label for="address[line1]">Address</label>
			<input type="text" name="address[line1]" value="<?= $form["address"]["line1"]?>" id="address1" size="40">
		</li>
	
		<li>
			<label for="address[line2]">Line 2</label>
			<input type="text" name="address[line2]" value="<?= $form["address"]["line2"]?>" id="address1" size="40">
		</li>
	
		<li>
			<label for="address[zip_postal_code]">Postal / Zip Code</label>
			<input type="text" name="address[zip_postal_code]" value="<?= $form["address"]["zip_postal_code"]?>" id="zip" size="10">
		</li>
	</ol>            
	</fieldset>
	<div class="buttonHolder">
              <button class="red cancel">Cancel</button>
              <button type="submit" class="submit">Save details</button>
              <span>And</span>
              <input type="radio" name="return" value="1" id="return" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked'; ?>/>
              <label class="fluid" for="return">Add another contact</label>
              <input type="radio" name="return" value="2" id="return" class="plain" <?PHP if ($_POST["return"]==2 || !$_POST) echo 'checked'; ?> />
              <label class="fluid" for="return">Return to contacts list</label>
        </div>
    </form>


