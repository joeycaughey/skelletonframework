<?php
global $ModuleBlogModel;
$BlogPosts = $ModuleBlogModel->find("WHERE id=id ORDER BY date_added LIMIT 0,50", true);
header("Content-Type: text/xml"); 

?>
<?='<?xml version="1.0" ?>'?>
<rss version="2.0">
<channel> 
	<?php foreach($BlogPosts as $post) :?>
	<item> 
	    <title><?=$post["title"]?></title>     
	    <link><?=get_uri("blog_view_url", array("id" => $post["id"]))?></link> 
	    <description></description> 
	</item>  
    <?php endforeach; ?>
</channel>
</rss>