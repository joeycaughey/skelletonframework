<?PHP
global $ContactsModel;
global $AddressesModel;
global $ModuleMailingListsModel;
global $ModuleMailingListsContactsModel;

$MailingList = $ModuleMailingListsModel->find_by_slug("title", $_GET["mailinglist"]);

if ($_POST) {
	if ($_POST["op"]) {
		
		foreach($_POST["ids"] as $id) {
			if ($_POST["op"]=="delete") $ModuleMailingListsContactsModel->delete(str2int($id));
		}
	}
}

$Contacts = $ModuleMailingListsContactsModel->find("WHERE mailinglist_id = '{$MailingList["id"]}'", true);

?>

<h2><a href="<?= get_uri("admin_module_mailinglists_url")?>">&lt;&lt; Back to Mail Services</a> |  Mailing Lists Module</h2>

<button class="add" href="<?= get_uri("admin_module_mailinglists_contact_add_url", array("mailinglist" => FORMAT_forurl($MailingList["title"]))) ?>">Add Contact</button>
<button class="export" href="<?= get_uri("admin_module_mailinglists_export_url", array("id" => $MailingList["id"])) ?>">Export Contacts to CSV</button>
  
<h3>Contacts &gt; <?=$MailingList["title"]?></h3>
<form method="POST">
    <table class="list" cellspacing="0">
        <thead>
            <tr>
            <th>&nbsp;</th>
            <th>Name</th>
                <th>Email</th>
                <th>Location</th>
                <th>Date Added</th>
                <th class="func">Functions</th>
            </tr>
        </thead>
        <tbody>
        	<?php if (count($Contacts)>0) : ?>
	            <?PHP foreach($Contacts as $contact) : ?>
	            <tr>
	            <td><input type="checkbox" name="ids[]" value="<?=$contact["id"]?>" /></input></td>
	                <td><a href="<?= get_uri("admin_module_mailinglists_contact_edit_url", array("mailinglist" => FORMAT_forurl($MailingList["title"]), "id" => $contact["id"])); ?>"><?= $ContactsModel->display_name($contact["contact_id"])?></a></td>
	                <td><?= $contact["contact"]["email"] ?></td>
	                <td><?= ($AddressesModel->quick_display($contact["contact"]["address_id"])) ? $AddressesModel->quick_display($contact["contact"]["address_id"]) : 'Not Entered' ?></td>
	                <td><?= date("M d, Y", $contact["date_added"])?></td>
	                <td class="func">
	                    <a href="<?= get_uri("admin_module_mailinglists_contact_edit_url", array("mailinglist" => FORMAT_forurl($MailingList["title"]), "id" => $contact["id"])); ?>" class="func edit" title="Edit this region">Edit</a>
	                </td>
	            </tr>
	            <?PHP endforeach; ?>
		    <?php else:?>
			<tr>
				<td colspan="5">There are no contacts in this mailing lists.</td>
			</tr>
			<?php endif;?>
        </tbody>
    </table>
    <div style="padding: 6px;">
	    <select name="op">
		    <option value="delete">Delete Selected</option>
		    <option value="activate">Activate Selected</option>
		    <option value="disable">Disable Selected</option>
	    </select>
	    <button>Go</button>
    </div>
    
</form>
