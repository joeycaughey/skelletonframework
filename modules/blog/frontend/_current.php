<?php
global $ModuleBlogModel;
$CurrentBlogPosts = $ModuleBlogModel->find("WHERE id=id ORDER BY date_added LIMIT 0,5", true); 
?>
<ul class="blog-post-list">
<?php if (count($CurrentBlogPosts)==0) : ?>
	<li>There are currently no blog posts.</li>
<?php else : 	?>
	<?php foreach($CurrentBlogPosts as $post) :  ?>
		<li>
			<a href="<?= get_uri("module_blog_view_url", array("id" => $post["id"]))?>">
				<?=parse_content($post["title"])?><span>&nbsp;&nbsp;</span>
			</a>

		</li>
	<?php endforeach; ?>
<?php endif; ?>
</ul> 