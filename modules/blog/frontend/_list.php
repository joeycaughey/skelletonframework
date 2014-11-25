<?PHP 
global $ModuleBlogModel;

$Blog = $ModuleBlogModel->find("WHERE id=id ORDER BY date_added DESC", true);
?>
<h2>News @ Bigsoul</h2>
<?php if(count($Blog)==0): ?>
<p>There is currently no news.</p>
<?php else : ?>
<ul>
	<?php foreach($Blog as $blog): ?>
	    <li>
	        <a href="<?=get_uri("module_blog_view_url", array("id" => $blog["id"]))?>" title="<?=parse_content($blog["title"])?>">
	            <?=parse_content($blog["title"])?>
	        </a>
	    </li>
	<?php endforeach; ?>
</ul>
<?php endif;?>

