<?PHP
global $ModuleBlogModel;


if ($_POST) {
    if ($_POST["op"]) {
        if (is_array($_POST["ids"])) {
            foreach($_POST["ids"] as $id) {
                if ($_POST["op"]=="delete") $ModuleBlogModel->delete(str2int($id));
                else if ($_POST["op"]=="disable") $ModuleBlogModel->disable(str2int($id));
                else if ($_POST["op"]=="activate") $ModuleBlogModel->activate(str2int($id));
            }
        }
    }
}

$BlogPosts = $ModuleBlogModel->find("WHERE id=id ORDER BY date_added DESC ", true);
?>
<h2><a href="<?= get_uri("admin_index_url") ?>">&lt;&lt; Back to CMS</a> | Blog Items</h2>

<?php if (config("editMode") == "wizard"):?>
<p class="message info">All blog items currently posted on your website
are listed here. </p>
<?php endif; ?>

<h3>
	<a href="<?= get_uri("module_blog_cms_add_url") ?>">Add blog post</a>
	Blog
</h3>
<table cellspacing="0" class="list">
	<thead>
		<tr>
		    <th width="1%">&nbsp;</th>
			<th>Item Name</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
	<?PHP if (count($BlogPosts)>0) : ?>
		<?php foreach($BlogPosts as $post): ?>
		<tr>
		    <td><input type="checkbox" name="ids[]" value="<?=$faq["id"]?>" /></td>
			<td>
				<a href="<?= get_uri("module_blog_cms_edit_url", array("id" => $post["id"])) ?>" title="Edit this news item">
					<?= ($post["title"]) ? parse_content($post["title"]) : truncate(strip_tags(parse_content($post["article"])), 80) ?>
				</a>
			</td>
			<td><?=FORMAT_date_ago($post["date_added"]);?></td>
			
		</tr>
		<?php endforeach; ?>
	<?php else:?>
		<tr>
			<td colspan="4">There are no blog entries.</td>
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