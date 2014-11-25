<?PHP
global $UserAccessModel;

if ($_POST) {
	if ($_POST["op"]) {
		if (is_array($_POST["ids"])) {
			foreach($_POST["ids"] as $id) {
				if ($_POST["op"]=="delete") $UserAccessModel->delete(str2int($id));
				else if ($_POST["op"]=="disable") $UserAccessModel->disable(str2int($id));
				else if ($_POST["op"]=="activate") $UserAccessModel->activate(str2int($id));
			}
		}
	}
}

$Access = $UserAccessModel->find("WHERE id=id AND group_id = '{$_GET["group_id"]}' ORDER BY status DESC, code", true);

?>


<h2>Group Access</h2>
    
<?php if (config("editMode") == "wizard") : ?>
<div class="message info">
	This is a quick listing of all the access of the groups associated with your website.
</div>
<br />
<?php endif; ?>
	
<button class="add" href="<?= get_uri("module_users_admin_access_add_url", array("group_id" => $_GET["group_id"]))?>">Add an access</button>

<h3>Access</h3>

<form method="POST">
	<input type="hidden" name="action" value="" />
	<table cellspacing="0" class="list">
		<thead>
			<tr>
				<th align="center"><input type=button value="Select All" onClick="this.value=check(this.form.elements['ids[]'])"> </th>
				<th>Code</th>
				<th>Description</th>
				<th>Status</th>
				<th class="func">Functions</th>
			</tr>
		</thead>
		
		
		<SCRIPT LANGUAGE="JavaScript">
		<!-- Begin
		var isSelected = "false";
		function check(field) {
		if (isSelected == "false") {
		  for (i = 0; i < field.length; i++) {
		  field[i].checked = true;}
		  isSelected = "true";
		  return "Diselect All"; }
		else {
		  for (i = 0; i < field.length; i++) {
		  field[i].checked = false; }
		  isSelected = "false";
		  return "Select All"; }
		}
		//  End -->
		</script>


		<tbody>
			<?PHP if (count($Access)>0) : ?>
			<?php foreach($Access as $acc): ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" value="<?= $acc["id"] ?>" /></td>
					<td align="center"><a href="<?=get_uri("module_users_admin_access_edit_url", array("group_id" => $acc["group_id"], "access_id" => $acc["id"]))?>"><?= ($acc["code"]) ? $acc["code"] : 'None Entered' ?></a></td>
					<td align="center"><?= ($acc["description"]) ? $acc["description"] : 'None Entered' ?></td>
					<td align="center"><?= $acc["status"] ?></td>
					<td class="func" align="center">
						<a href="<?= get_uri("module_users_admin_access_edit_url", array("group_id" => $acc["group_id"], "access_id" => $acc["id"]))?>"> Edit </a>
		            </td>
		        </tr>
			<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan="4">There are no Access</td>
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
