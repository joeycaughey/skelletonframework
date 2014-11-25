<?php
global $CONFIG;
global $ContentModel;
global $ModuleMailServicesNewslettersModel;
global $FilesModel;

if ($_GET["id"]) {
	$form = $ModuleMailServicesNewslettersModel->find(str2int($_GET["id"]));
} 

if ($_POST) {
	$VALID = array();
	$VALID["title"] = validate($_POST["title"]!="", "You must enter a title.");

	if (isnotnull($VALID)) {
	
		if ($_GET["id"]) {
			$ModuleMailServicesNewslettersModel->update($_POST, $_GET["id"]);
		} else {
			$_GET["id"] = $ModuleMailServicesNewslettersModel->insert($_POST);
		}
		
		print_r($_FILES);
		
		// File Upload
		if ($_FILES["file"]["error"]==0 AND $_FILES["file"]["size"]>0) {	
				
			$FilesModel->resource("module_newsletters", $_GET["id"], "pdf");
			if ($form["file_id"]) {
				$FilesModel->delete(str2int($form["file_id"]));
			}
			$_POST["file_id"] = $FilesModel->add($_FILES["file"], $_POST["title"], $_POST["description"]);
			$ModuleMailServicesNewslettersModel->update($_POST, $_GET["id"]);
		}
		
		if ($_POST["return"]==2) header("Location: ".get_uri("admin_module_newsletters_url"));
		else if ($_POST["return"]==3) $ContentModel->preview_module("module_newsletters");
		else header("Location: ".get_uri("admin_module_newsletters_edit_url", array("id" => $_GET["id"])));
	}
	$form = $_POST;
} 

if ($_GET["id"]) {
	$form = $ModuleMailServicesNewslettersModel->find(str2int($_GET["id"]));
} 


?>

<h2><a href="<?= get_uri("admin_module_newsletters_url")?>">&lt;&lt; Back to Newsletter Management</a> | <?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Newsletter</h2>
        
<?= display_feedback() ?>
<form action="" method="post" enctype="multipart/form-data" class="standard">
	<fieldset>
		<legend>Newsletter Entry</legend>    
		<ol>
			<li>
				<label for="title">Title</label>
				<input class="required" type="text" name="title" value="<?=parse_content($form["title"])?>" id="title" size="40" />
			</li>
			<?php if($_GET["id"] && $form["file_id"]):?>
			<li><label>Current</label>
			    <span><img src="<?=$FilesModel->get_url($form["file_id"], "jpg")?>" alt="Thumb" width="100"></span>
			</li>
			<?php endif;?>

			<li class="file">
			    <label for="file" title="PDF file">Upload <?=($_GET["id"]) ? 'New' : '' ?> PDF</label>
				<input type="file" name="file" id="file" value="" size="20" class="file">
			</li>			
	
		</ol>	            
	</fieldset>
        
    <div class="buttonHolder">
		<button type="submit" class="submit"><?= ($_GET["id"]) ? 'Save' : 'Add' ?> Newsletter</button>
		<button class="red cancel">Cancel</button>
		<span>And</span>
		<input type="radio" name="return" value="1" id="return1" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked' ?>/>
		<label class="fluid" for="return1">Remain on this page</label>
		<input type="radio" name="return" value="2" id="return2" class="plain" <?PHP if ($_POST["return"]==2 || !$_POST) echo 'checked' ?>/>
		<label class="fluid" for="return2">Return to newsletters listing</label>
	</div>
</form>
