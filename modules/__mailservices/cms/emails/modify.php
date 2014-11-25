<?PHP
global $CONFIG;
global $ModuleMailServicesEmailTemplatesModel;

if ($_POST) {
	$VALID = array();
	$VALID["template_id"] = validate($_POST["title"]!="", "You must enter a template id.");
	$VALID["subject"] = validate($_POST["title"]!="", "You must enter a subject");
	$VALID["body"] = validate($_POST["body"]!="", "You must enter an email body.");

	if (isnotnull($VALID)) {
		$_POST["from"] = ($_POST["from"]=="(default)") ? "" : $_POST["from"];
		$_POST["from_name"] = ($_POST["from_name"]=="(default)") ? "" : $_POST["from_name"];
		
		if ($_GET["id"]) {
			$ModuleMailServicesEmailTemplatesModel->update($_POST, $_GET["id"]);
		} else {
			$_GET["id"] = $ModuleMailServicesEmailTemplatesModel->insert($_POST);
		}
		
		if ($_POST["return"]==2) header("Location: ".get_uri("admin_module_mailinglists_emails_url"));
		else header("Location: ".get_uri("admin_module_mailinglists_emails_edit_url", array("id" => $_GET["id"])));
	}
	$form = $_POST;
} 

if ($_GET["id"]) {
	$form = $ModuleMailServicesEmailTemplatesModel->find(str2int($_GET["id"]));
} 
?>

<h2><a href="<?= get_uri("admin_module_mailinglists_emails_url")?>">&lt;&lt; Back to Email Templates</a> | <?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Email Template</h2>
        
<?php if (config("editMode") == "wizard"):?>
<p class="message info">
	Please note that when editing an email template, all variable parameters are required.
</p>
<?php endif; ?>
<h3><?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Email Template</h3>
<form action="" method="post" enctype="multipart/form-data">
	<fieldset>
 
		<ol>
			<li>
				<label for="template_id" title="Please enter a template id for this email template">Template ID</label>
				<input class="required" type="text" name="template_id" value="<?=parse_content($form["template_id"])?>" id="title" size="40" />
			</li>
			<li>
				<label for="from" title="Please enter a from email address for this email template">From</label>
				<input class="required" type="text" name="from" value="<?= ($form["from"]) ? parse_content($form["from"]) : "(default)" ?>" id="title" size="40" />
			</li>
			<li>
				<label for="from_name" title="Please enter a from name for this email template">From Name</label>
				<input class="required" type="text" name="from_name" value="<?= ($form["from_name"]) ? parse_content($form["from_name"]) : "(default)" ?>" id="title" size="40" />
			</li>
			<li>
				<label for="subject" title="Please enter a subject for this email template">Subject</label>
				<input class="required" type="text" name="subject" value="<?=parse_content($form["subject"])?>" id="title" size="40" />
			</li>
			<li>
				<label for="article" title="Enter an body for your email template">Body</label>		
				<textarea name="article" id="text"  cols="62" rows="20"><?=parse_content($form["body"])?></textarea>
			</li>
		</ol>	            
	</fieldset>
        
    <div class="buttonHolder">
		<button type="submit" class="submit"><?= ($_GET["id"]) ? 'Save' : 'Add' ?> Email Template</button>
		<button class="red cancel">Cancel</button>
		<span>And</span>
		<input type="radio" name="return" value="1" id="return1" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked' ?>/>
		<label class="fluid" for="return1">Remain on this page</label>
		<input type="radio" name="return" value="2" id="return2" class="plain" <?PHP if ($_POST["return"]==2 || !$_POST) echo 'checked' ?>/>
		<label class="fluid" for="return2">Return to Blog listing</label>
	</div>
</form>


