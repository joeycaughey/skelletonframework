<?PHP
global $ModuleMailingListsModel;

$MailingLists = $ModuleMailingListsModel->find("WHERE id=id ORDER BY title", true);


?>
<h2><a href="<?= get_uri("admin_module_mailinglists_url")?>">&lt;&lt; Back to Mail Services</a> |  Mailing Lists Module</h2>

    
<h3>
    <a href="<?= get_uri("admin_module_mailinglists_add_url"); ?>">
        Add a Mailing List
    </a>
    Current Lists
</h3>
<table class="list" cellspacing="0">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Contacts</th>
                <th>Added</th>
                <th class="func">Functions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($MailingLists)>0) : ?>
            <?PHP foreach($MailingLists as $list) : ?>
            <tr>
                <td><a href="<?= get_uri("admin_module_mailinglists_contacts_url", array("mailinglist" => FORMAT_forurl($list["title"]))) ?>"><?= $list["title"]?></a></td>
                <td><?= $ModuleMailingListsModel->contacts($list["id"]) ?></td>
                <td><?= $list["type"] ?></td>
                <td><?= FORMAT_date_ago($list["date_added"])?></td>
                <td class="func">
	                <?php if ($list["id"]!=1) :?>
	                    <a href="<?= get_uri("admin_module_mailinglists_delete_url", array("id" => $list["id"])); ?>" class="func del" title="Delete this region">Delete</a>
                    <?php endif; ?>
                    <a href="<?= get_uri("admin_module_mailinglists_edit_url", array("id" => $list["id"])); ?>" class="func edit" title="Edit this region">Edit</a>
                </td>
            </tr>
            <?PHP endforeach; ?>
            <?php else:?>
        <tr>
            <td colspan="5">There are no mailing lists.</td>
        </tr>
    <?php endif;?>
    </tbody>
</table>
