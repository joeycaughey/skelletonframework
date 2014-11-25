<?php 
global $ModuleBlogModel;

$MostPopularArticles = $ModuleBlogModel->most_popular()

?>
<h2>most<span>popular&nbsp;</span></h2>
<?php if (count($MostPopularArticles)==0) : ?>
	<p>No articles.</p>
<?php else :?>
	<?php foreach($MostPopularArticles as $article) : ?>
		<div class="blog_post">
			<h4><a href="<?= get_uri("blog_view_url", array("id" => $article["id"]))?>"><?=$article["title"]?><span>&nbsp;&nbsp;</span></a></h4>
			<p class="first">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis 
			et augue ipsum... </p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

