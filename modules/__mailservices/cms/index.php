<h2><a href="<?= get_uri("admin_index_url") ?>">&lt;&lt; Back to CMS</a> | Emails, Mailing Lists and Newsletters</h2>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">
	All configuration settings associated with your email, mailinglists, newsletters, and 
	contacts are located here.
</p>
<?php endif; ?>


<ul class="functions">
	<li class="header">Base Functions</li>
	<li class="settings_global first">
    	<a href="<?= get_uri("admin_module_mailinglists_emails_url") ?>" title="Manage Emails">
        	<strong>Manage Emails</strong> Manage your emails.
		</a>
	</li>
	<li class="settings_global">
    	<a href="<?= get_uri("admin_module_mailinglists_list_url") ?>" title="Manage Mailing Lists">
        	<strong>Mailing Lists</strong> Manage your mailinglists and contacts.
		</a>
	</li>
	
	
	<li class="settings_global first">
		<a href="<?= get_uri("admin_module_newsletters_url") ?>" title="Manage Newsletters">
			<strong>Newsletter Management</strong> Manage your newsletters here.
		</a>
	</li>
	
	<li class="settings_global ">
		<a href="<?= get_uri("admin_module_mailinglists_settings_url") ?>" title="Manage Newsletters">
			<strong>Newsletter Settings</strong> Manage your newsletter settings here.
		</a>
	</li>
</ul>
