<?PHP
global $CONFIG;
global $ModuleMailingListsModel;

if ($_POST) {
    $VALID = array();
    $VALID["title"] = validate($_POST["title"]!="", "You must enter a mailinglist title.");

    if (isnotnull($VALID)) {

        if ($_GET["id"]) {
            $ModuleMailingListsModel->update($_POST, "WHERE id = '{$_GET["id"]}'");
        } else {
            $ModuleMailingListsModel->insert($_POST);
        }
        
        if ($_POST["return"]==2) header("Location: ".get_uri("admin_module_mailinglists_list_url"));
        unset($_POST);
    }
    
    $form = $_POST;
}


if ($_GET["id"]) {
    $form = $ModuleMailingListsModel->find(str2int($_GET["id"]));
} 

?>

<h2><a href="<?= get_uri("admin_module_mailinglists_url")?>">&lt;&lt; Back to Mail Services</a> |  Add a new mailing list</h2>
    <?= display_feedback(); ?>
<?php if (config("editMode") == "wizard"):?>
    <p class="message info">
        Please provide inforamation regarding this mailing list.
    </p>
<?php endif; ?>

    <form  method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>List information</legend>
            <ol>            
                <li>
                <label for="title" title="Please enter a title for this mailinglist">List Title</label>
                    <input type="text" name="title" id="title" size="30" value="<?=$form["title"] ?>" class="required" />
                </li>   
            </ol>            
        </fieldset>
        <div class="buttonHolder">
              <button class="red cancel">Cancel</button>
              <button type="submit" class="submit">Save details</button>
              <span>And</span>
              <input type="radio" name="return" value="1" id="return" class="plain" <?PHP if ($_POST["return"]==1) echo 'checked'; ?>/>
              <label class="fluid" for="return">Add another mailinglist</label>
              <input type="radio" name="return" value="2" id="return" class="plain" <?PHP if ($_POST["return"]==2 || !$_POST) echo 'checked'; ?> />
              <label class="fluid" for="return">Return to video list</label>
        </div>
    </form>
