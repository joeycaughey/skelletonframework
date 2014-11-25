<?php
global $CONFIG;
global $ModuleStoreCategoriesModel;

if ($_POST) {
	$VALID = array();
	$VALID["name"] = validate($_POST["name"]!="", "You must enter a category name.");
	
	if(isnotnull($VALID)) {
		if ($_GET["id"]) {
			$ModuleStoreCategoriesModel->update($_POST, $_GET["id"]);
		} else {
			$ModuleStoreCategoriesModel->insert($_POST);
		}

		if ($_POST["return"]==2) header("Location: ".get_uri("module_store_cms_url"));
		unset($_POST);
	}
	
	$form = $_POST;
}


if ($_GET["id"]) {
	$form = $ModuleStoreCategoriesModel->find(str2int($_GET["id"]));	
}

?>

<h2><?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Store Category</h2>
<?= display_feedback() ?>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">
	Please fill in as much information as you would like displayed about this category.
</p>
<?php endif; ?>
	    
<form method="post" enctype="multipart/form-data">
	<fieldset>  
		<legend>Category Information</legend>
            <ol>
                <li>
                    <label for="name" title="Enter the name of the group">Category</label>
                    <input class="required" type="text" name="name" value="<?=parse_content($form["name"])?>" id="name" size="30" minlength="1" maxlength="30" />
                </li>
                
                 <li>
                    <label for="description" title="Provide additional information for this group.">Description</label>
                    <div class="rteHolder">
                        <textarea name="description" id="description" class="rte" rows="20" cols="72"><?=parse_content($form["description"])?></textarea>
                    </div>                    
                </li>
            </ol>
	</fieldset>
        
	<div class="buttonHolder">
		<button type="button" onClick="javascript:history.back();" class="red cancel">Cancel</button>
		<button type="submit">Save Settings</button>
		<span>And</span>
		<input type="radio" name="return" value="1" id="return1" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked'; ?>>
		<label class="fluid" for="return1"><?= ($_GET["id"]) ? 'Return to this group' : 'Add another group' ?></label>			
		<input type="radio" name="return" value="2" id="return2" class="plain" <?PHP if ($_POST["return"]==2 or !$_POST) echo 'checked'; ?> />
		<label class="fluid" for="return2">Return to productions list</label>
	</div>      
</form>

