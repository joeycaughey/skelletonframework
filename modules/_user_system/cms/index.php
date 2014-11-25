
<h2><a href="<?= get_uri("admin_index_url") ?>">&lt;&lt; Back to CMS</a> | User Administration</h2>

<?php if (config("editMode") == "wizard"):?>
<div class="message info">
	This section allows you to manage either your users, groups, and permission settings.
	
</div>
<?PHP endif; ?>


<ul class="functions" style="width:100%">
	<li class="header">
		Base Functions
	</li>
	<li class="first">
		<a href="<?= get_uri("module_users_admin_list_url") ?>" title="Users / Privilages"> 
			<strong>Users / Privilages</strong> List, manage users and privilages
		</a>
	</li>
	<li class="">
		<a href="<?= get_uri("module_users_admin_groups_url") ?>" title="Groups"> 
			<strong>Groups</strong> List, manage user groups
		</a>
	</li>

</ul>
