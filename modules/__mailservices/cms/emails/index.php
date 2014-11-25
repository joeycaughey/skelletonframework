<?PHP
global  $ModuleMailServicesEmailTemplatesModel;
global $ModuleMailingListsModel;

$Emails = $ModuleMailServicesEmailTemplatesModel->find("WHERE id=id ORDER BY subject", true);
?>

<h2><a href="<?= get_uri("admin_module_mailinglists_url")?>">&lt;&lt; Back to Mail Services</a> | Email Templates </h2>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">
	You may edit the email content that your site sends out. Below is a list of all your current email templates.  
</p>
<?php endif; ?>


<h3>
	<a href="<?= get_uri("admin_module_mailinglists_emails_add_url")?>" class="add">+ Add an Email Template</a>
	Current Email Templates
</h3>
<table class="list" cellspacing="0">
	<thead>
		<tr>
			<th>Template ID</th>
            <th>Subject</th>
			<th class="func">Functions</th>
		</tr>
	</thead>
	<tbody>
	<?php if (count($Emails)>0) : ?>
		<?PHP foreach($Emails as $email) : ?>
			<tr>
                <td><a href="<?= get_uri("admin_module_mailinglists_emails_edit_url", array("id" => $email["id"])) ?>"><?= $email["template_id"]?></a></td>
                <td><?= $email["subject"] ?></td>
                <td class="func" align="right">
                    <a href="<?= get_uri("admin_module_mailinglists_emails_edit_url", array("id" => $email["id"])); ?>" class="func edit" title="Edit this email template">Edit</a>
                </td>
            </tr>
		<?PHP endforeach; ?>
	<?php else:?>
		<tr>
			<td colspan="5">There are currently no email templates.</td>
		</tr>
	<?php endif;?>
	</tbody>
</table>

