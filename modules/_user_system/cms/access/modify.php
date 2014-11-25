<?PHP
global $CONFIG;
global $UserAccessModel;

if ($_POST) {
		
		$_POST["group_id"] = $_GET["group_id"];
		if ($_GET["access_id"]) {
			$UserAccessModel->update($_POST, $_GET["access_id"]);
		} else {
			$UserAccessModel->insert($_POST);
		}

	$form = $_POST;
} 

if ($_GET["access_id"]) {
	$form = $UserAccessModel->find(str2int($_GET["access_id"]));
} 
?>

<h2><?= ($_GET["access_id"]) ? 'Edit' : 'Add' ?> Access </h2>
        
<?php if (config("editMode") == "wizard"):?>
<p class="message info">
	 Please fill in as much information as you would like displayed about this access
</p>
<?php endif; ?>
<?= display_feedback() ?>
<form action="" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Access</legend>    
		<ol>
			<li>
				<label for="code" title="Please enter a code for this Access">code</label>
				<input class="required" type="text" name="code" value="<?=parse_content($form["code"])?>" id="title" size="40" />
			</li>
			<li>
				<label for="description" title="Please enter a description for this Access">description</label>
				<input type="text" name="description" value="<?=parse_content($form["description"])?>" id="title" size="40" />
			</li>
		</ol>	            
	</fieldset>

        
   <div class="buttonHolder">
	<button type="button" onClick="javascript:history.back();" class="red cancel">Cancel</button>
	<button type="submit" class="submit"><?= ($_GET["access_id"]) ? 'Save' : 'Add' ?> Access</button>
	<span>And</span>
	<input type="radio" name="return" value="1" id="return1" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked'; ?>>
	<label class="fluid" for="return1">Add another link</label>			
	<input type="radio" name="return" value="2" id="return2" class="plain" <?PHP if ($_POST["return"]==2 or !$_POST) echo 'checked'; ?> />
	<label class="fluid" for="return2">Return to link list</label>
  </div>      
 
</form>


