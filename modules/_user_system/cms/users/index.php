<?PHP
global $ContactsModel;
global $UsersModel;
global $UsersModulesModel;
global $UserPrivilagesModel;
global $ModuleUserLoginLogModel;

if ($_POST) {
	if ($_POST["op"]) {
		if (is_array($_POST["ids"])) {
			foreach($_POST["ids"] as $id) {
				if ($_POST["op"]=="delete") $UsersModel->delete(str2int($id));
				else if ($_POST["op"]=="disable") $UsersModel->disable(str2int($id));
				else if ($_POST["op"]=="activate") $UsersModel->activate(str2int($id));
			}
		}
	}
}

$Users = $UsersModel->find("WHERE id=id", true);

?>
<h2><a href="<?= get_uri("module_users_admin_url") ?>">&lt;&lt; Back to User/Groups Management</a> | Users Management</h2>
    
<?php if (config("editMode") == "wizard") : ?>
<div class="message info">
	This is a quick listing of all the users associated with your website.
</div>
<?php endif; ?>



<h3>
	<a href="<?= get_uri("module_users_admin_add_url") ?>">Add A User</a>
	Manage Users
</h3>


<form method="POST">
	<input type="hidden" name="action" value="" />
	<table cellspacing="0" class="list">
		<tr class="options">
			<td colspan="7">
				<label>Search</label>
				<input type="text" name="keywords" value="<?= $_POST["keywords"] ?>" size="30" />
				<button type="submit">Find</button>
			</td>
		</tr>
		<tr>
			<th style="width: 1%;">&nbsp;</th>
			<th>Full Name</th>
			<th>Email/Username</th>
			<th>Privilages</th>
			<th class="center">Login<br />Attempts</th>
			<th>Status</th>
			<th class="func">Functions</th>
		</tr>
		<tbody>
			<?PHP if (count($Users)>0) : ?>
			<?php foreach($Users as $user): 
				//$name = $ContactsModel->display($user["contact"]["id"])
				
				?>
				<?php if ($user["email"]!="") : ?>
				<tr>
					<td><input type="checkbox" name="ids[]" value="<?= $user["id"] ?>" /></td>
					<td><?= ($name) ? $name : 'None Entered' ?></td>
					<td><?= ($user["email"]) ? $user["email"] : $user["contact"]["email"] ?></td>
					<td>
						<?php if ($user["email"]=="admin") : ?>
							--
						<?php else : ?>
							<a href="<?= get_uri("module_users_cms_login_url", array("id" => $user["id"])) ?>" target="_blank">
								LOGIN
							</a>
						<?php endif; ?>
					</td>
					<td align="center">
						<?=$ModuleUserLoginLogModel->attempts($user["id"])?> Attempts
					</td>
					<td><?= $user["status"] ?></td>
					<td class="func">
						<a href="<?=get_uri("module_users_admin_delete_url", array("id" => $user["id"]))?>" class="func del" title="Delete this user">Delete</a>
		                <a href="<?=get_uri("module_users_admin_edit_url", array("id" => $user["id"]))?>" class="func edit" title="Edit this user">Edit</a> 
		            </td>
		        </tr>
		        <?php endif; ?>
			<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan="4">There are no users</td>
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
