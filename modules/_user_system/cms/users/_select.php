<?php 
global $UserGroupsModel;
$Groups = $UserGroupsModel->find("WHERE id = id", true);
?>
<form action="" method="post" class="search_form">
	<button type="submit" class="search">Search</button>
	<input type="text" name="keywords" value="" size="25"/>

	<label for="group_id">in</label>
    <select name="group_id" onchange="userSelectForm.submit()">
    	<option value="">All Groups</option>
 		<?php foreach($Groups as $group) : ?>
 			<option value="<?=$group["id"]?>"><?=$group["description"]?></option>
 		<?php endforeach; ?>
    </select>
</form>

<style>
	form.search_form {
		padding: 10px;
		background: #eee;
		border-top: solid 1px #ccc;
	}
	
	form.search_form input {
		font-size: 12px;
		border: solid 1px #666;
		padding: 5px;
	}
	
	form.search_form button {
		float: right;
	}
</style>
    