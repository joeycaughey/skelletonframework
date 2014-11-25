	<div id="rightCol">
		<h3>User listing</h3>
<?php if ($this->sitecfg->getValue("editMode") == "wizard"):?>
		<div class="note">
			This is a quick listing of all the users associated with your website.
			<ul>
				<li><strong>Administrators</strong> are users who will manage content on this website</li>
				<li><strong>Public Users</strong> are users with granted privileges to your website (ie. Clients as part of the E-Commerce module)</li>
			</ul>
		</div>
<?php endif; ?>

		<h4>Ecommerce Users</h4>
		<table class="sortable-onload-2">
			<thead>
				<tr>
					<th class="sortable" style="width: 10%;">ID</th>
					<th class="sortable">Full Name</th>
					<th class="sortable">User Name</th>
					<th class="func">Functions</th>
				</tr>
			</thead>
			<tbody>
<?php if (is_array($userList = $this->userModObj->listOrdered())): ?>
<?php foreach($userList as $userObj): ?>
				<tr>
					<td><?=$userObj->id;?></td>
					<td><?=$userObj->firstname;?> <?=$userObj->lastname;?></td>
					<td><?=$userObj->username;?></td>
					<td><a href="<?=$this->path;?>&subFunc=editUser&id=<?=$userObj->id;?>" class="button edit" title="Edit this user">Edit</a> |
						<a href="javascript:confirmDel('<?=$this->path;?>&subFuncOption=deletePublic&id=<?=$userObj->id;?>');" class="button del" title="Delete this user">Delete</a>
					</td>
				</tr>
<?php endforeach; ?>
<?php else:?>
<tr><td colspan="4">There are no users</td></tr>
<?php endif;?>

			</tbody>
		</table>
	</div>