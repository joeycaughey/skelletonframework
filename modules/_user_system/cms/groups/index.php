<?PHP
global $UserGroupsModel;

if ($_POST) {
	if ($_POST["op"]) {
		if (is_array($_POST["ids"])) {
			foreach($_POST["ids"] as $id) {
				if ($_POST["op"]=="delete") $UserGroupsModel->delete(str2int($id));
				else if ($_POST["op"]=="disable") $UserGroupsModel->disable(str2int($id));
				else if ($_POST["op"]=="activate") $UserGroupsModel->activate(str2int($id));
			}
		}
	}
}

$Groups = $UserGroupsModel->find("WHERE id=id ORDER BY name", true);

?>

<h2><a href="<?= get_uri("module_users_admin_url") ?>">&lt;&lt; Back to User/Groups Management</a> | User Groups</h2>
    
<?php if (config("editMode") == "wizard") : ?>
<div class="message info">
	This is a quick listing of all the user groups associated with your website.
</div>
<?php endif; ?>
	
<h3>
	<a href="<?= get_uri("module_users_admin_groups_add_url") ?>">+ Add a Group</a>
	Groups
</h3>

<form method="POST">
	<input type="hidden" name="action" value="" />
	<table cellspacing="0" class="list">
		<thead>
			<tr>
				<th><input type="checkbox" name="checkall" value="1" /> </th>
				<th>Name</th>
				<th>Description</th>
				<th>Privilages</th>
				<th>Status</th>
				<th class="func">Functions</th>
			</tr>
		</thead>
		<tbody>
			<?PHP if (count($Groups)>0) : ?>
				<?php foreach($Groups as $group): ?>
					<tr>
						<td width="1%"><input type="checkbox" name="ids[]" value="<?= $group["id"] ?>" /></td>
						<td><a href="<?=get_uri("module_users_admin_groups_edit_url", array("id" => $group["id"]))?>"><?= ($group["name"]) ? $group["name"] : 'None Entered' ?></a></td>
						<td><?= ($group["description"]) ? $group["description"] : 'None Entered' ?></td>
						<td>--</td>
						<td><?= $group["status"] ?></td>
						<td class="func" align="center">
	               			<a href="<?= get_uri("module_users_admin_access_url", array("group_id" => $group["id"]))?>"> Manage Group Access </a> 
	            		</td>
			        </tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan="4">There are no groups</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
	<div style="padding: 5px;">
		<select name="op">
			<option value="">--With Selected--</option>
			<option value="activate">Activate Selected</option>
			<option value="disable">Disable Selected</option>
			<option value="delete">Delete Selected</option>
		</select>
		<button type="submit">Go</button>
	</div>
</form>
