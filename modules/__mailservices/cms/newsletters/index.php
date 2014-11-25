<?PHP
global $ModuleMailServicesNewslettersModel;

if ($_POST) {
	
	if ($_POST["action"]=="save") {
		foreach($_POST["config"] as $key => $value) {
			set_config($key, $value);
		}
	} else {
		if ($_POST["op"]) {
			if (is_array($_POST["ids"])) {
				foreach($_POST["ids"] as $id) {
					if ($_POST["op"]=="delete")  $ModuleMailServicesNewslettersModel->delete("WHERE id = '{$id}'");
					else if ($_POST["op"]=="disable") $ModuleMailServicesNewslettersModel->disable("WHERE id = '{$id}'");
					else if ($_POST["op"]=="activate") $ModuleMailServicesNewslettersModel->activate("WHERE id = '{$id}'");
				}
			}
		}
	}
}

$Newsletters = $ModuleMailServicesNewslettersModel->find("WHERE id=id ORDER BY date_added", true);
?>

<h2><a href="<?= get_uri("admin_module_mailinglists_url")?>">&lt;&lt; Back to Mail Services</a> | Manage Newsletters</h2>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">All newsletter items currently posted on your website
are listed here. These items can be implemented into any content page
dynamically. Simply add a "Site Newsletter" element when creating content
pages.</p>
<?php endif; ?>

<h3>Page Content</h3>
<form method="POST" style="margin-top: 5px;">
	<input type="hidden" name="action" value="save" />
	<textarea name="config[newsletter_content]" style="width: 98%; height: 100px"><?=config("newsletter_content")?></textarea>
	<button class="submit" type="submit">Save Page Content</button>
</form>




<h3>
	<a href="<?= get_uri("admin_module_newsletter_add_url") ?>">Add newsletter</a>
	Manage Newsletter
</h3>
<form method="POST">
<table cellspacing="0" class="list">
	<thead>
		<tr>
			<th></th>
			<th>Item Name</th>
			<th>Date</th>
			<th class="func">Functions</th>
		</tr>
	</thead>
	<tbody>
	<?PHP if (count($Newsletters)>0) : ?>
		<?php foreach($Newsletters as $newsletter): ?>
		<tr>
			<td><input type="checkbox" name="ids[]" value="<?=$newsletter["id"]?>" /></td>
			<td><?=parse_content($newsletter["title"])?></td>
			<td><?=date("m/d/Y",$newsletter["date_added"]);?></td>
			<td class="func"> <a
				href="<?= get_uri("admin_module_newsletter_edit_url", array("id" => $newsletter["id"])) ?>"
				class="func edit" title="Edit this newsletter item">Edit</a></td>
		</tr>
		<?php endforeach; ?>
	<?php else:?>
		<tr>
			<td colspan="4">There are no newsletters</td>
		</tr>
	<?php endif;?>
	</tbody>
</table>
<p>
	<select name="op">
		<option value="">--With Selected--</option>
		<option value="activate">Activate Selected</option>
		<option value="disable">Disable Selected</option>
		<option value="delete">Delete Selected</option>
	</select>
	<button type="submit">Go</button>
</p>

</form>
