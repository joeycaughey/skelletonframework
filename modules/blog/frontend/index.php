<?php
global $ModuleBlogModel;
global $FilesModel;


if ($_POST) {
	$keywords = explode(" ", $_POST["keywords"]);	

	$sql = array();
	foreach($keywords as $kw) {
		$sql[]= "title LIKE '%{$kw}%'";
		$sql[] = "article LIKE '%{$kw}%'";
	}
	$sql ="AND (".implode(" OR ", $sql).") ";
}

$CurrentBlogPosts = $ModuleBlogModel->find("WHERE id=id $sql ORDER BY date_added DESC LIMIT 0,30", true); 

load_asset("flowplayer");
load_asset("jquery.flash");
?>
<div class="bar">
	<h2 class="title">
		Blog
		<span> Get the latest on Marmelade and Design!</span>
	</h2>
</div>
<div class="inset">
	<div class="two-column-offset-right-layout">
		<div class="column first">
			<img src="/_templates/frontend/images/banners/blog.png" width="100%" />

			<?php if (count($CurrentBlogPosts)==0) : ?>
				<p>There are currently no blog posts <?= ($_POST["keywords"]) ? 'hat match your query' : '' ?>.</p>
			<?php else : 	?>
				<?php foreach($CurrentBlogPosts as $post) : 
					$ModuleBlogModel->viewed($post["id"]);
					$Files = $FilesModel->resource("blog-images", $post["id"]);
					$link = ' <p><b><a href="'.get_uri("module_blog_view_url", array("id" => $post["id"])).'">Read More &gt;&gt;</a></b></p>';
				?>
				<h3><?=parse_content($post["title"])?><span>&nbsp;</span></h3>
				<span>Posted <?= FORMAT_date_ago($post["date_added"])?> ago</span>
				<br />
				
			    
                <?php if ($Files[0]) : ?>
                <img src="<?=$FilesModel->get_url($Files[0]["id"])?>" alt="" border="0" width="200" align="left" style="margin-right: 2%; margin-top: 30px;" />
				<?php endif; ?>
				
				<p><?=truncate(parse_content(strip_tags($post["article"], "<br><b><ul><em>")), 400, $link)?> </p>
				<hr />
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	
		<div class="column">	
			<?php 
			include("modules/blog/frontend/_search.php"); 
			include("modules/blog/frontend/_twitter.php"); 
			include("modules/blog/frontend/_archives.php"); 
			//include("modules/blog/frontend/_mostpopular.php");
			?>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>