<?PHP
global $CONFIG;
global $UserGroupsModel;

if ($_GET["id"]) {
	$form = $UserGroupsModel->find(str2int($_GET["id"]));	
}

if ($_POST) {
	$VALID = array();
	$VALID["description"] = validate($_POST["description"], "You must enter a group description.");
	$VALID["login_url"] = validate($_POST["login_url"], "You must enter a login url;.");

	if (isnotnull($VALID)) {
		if ($_GET["id"]) {
			$UserGroupsModel->update($_POST, $_GET["id"]);
		} else {
			 $UserGroupsModel->insert($_POST);
		}
			
		if ($_POST["return"]==2) header("Location: ".get_uri("admin_users_admin_list_url"));
		unset($_POST);
	}
		
	$form = $_POST;
}

?>

<h2><?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Group</h2>

<?= display_feedback() ?>

<?php if (config("editMode") == "wizard" && false):?>
	<p class="message info"></p>
<?php endif; ?>

<form method="POST">
	<fieldset>
            <legend>Group Information</legend>
            <ol>
		<li>
                    <label for="name" title="Name">Name</label>
                    <input class="required" type="text" name="name" value="<?=$form["name"];?>" size="30" minlength="1" maxlength="30" />
                </li>
                <li>
                    <label for="description" title="Description of the group">Description</label>
                    <input class="required" type="text" name="description" value="<?=$form["description"];?>" size="30" minlength="1" maxlength="30" />
                </li>
                
                <li>
                    <label for="login_url" title="Login URL of the group">Login URL</label>
                    <input class="required" type="text" name="login_url" value="<?=$form["login_url"];?>" size="50" minlength="8" maxlength="255" />
                </li>
            </ol>
        </fieldset>
        
	<div class="buttonHolder">
		<button type="button" onclick="javascript:history.back();" class="red cancel">Cancel</button>	
		<button type="submit" class="submit"><?= ($_GET["id"]) ? 'Save' : 'Add' ?> Group</button>
		<span>And</span> 
		<input type="radio" name="return" value="1" id="return1" class="plain"  <?PHP if ($_POST["return"]==1) echo 'checked'; ?> /> 
		<label class="fluid" for="return1">Create another group</label> 
		<input type="radio" name="return" value="2" id="return2" class="plain" <?PHP if ($_POST["return"]==2 || !$_POST) echo 'checked'; ?>/> 
		<label class="fluid" for="return2">Return to groups list </label>
	</div>
</form>
