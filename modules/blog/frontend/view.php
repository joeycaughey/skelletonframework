<?php
global $ModuleBlogModel;
global $FilesModel;
global $ImagesModel;

$CurrentBlogPost = $ModuleBlogModel->find("WHERE id='{$_GET["id"]}'", false); 


$this->template->set_variable("META_DESCRIPTION", strip_tags($CurrentBlogPost["meta_description"]));
$this->template->set_variable("META_KEYWORDS", strip_tags($CurrentBlogPost["meta_keywords"]));	

$Files = $FilesModel->resource("blog-images", $CurrentBlogPost["id"]);
//load_asset("flowplayer");
//load_asset("jquery.flash");
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
            
			<?php if (count($CurrentBlogPost)==0) : ?>
				<p>Article has been deleted or does not exist.</p>
			<?php else : 	?>
				<?php 
					$ModuleBlogModel->viewed($CurrentBlogPost["id"]);
				?>
				<h3><?=parse_content($CurrentBlogPost["title"])?><span>&nbsp;</span></h3>
				<span>Posted <?= date("m/d/Y @ H:i:s", $CurrentBlogPost["date_added"])?> </span>
				<br />
				 <?php if ($Files[0]) : ?>
                <img src="<?=$FilesModel->get_url($Files[0]["id"])?>" alt="" border="0" width="200" align="left" style="margin-right: 2%; margin-top: 30px;" />
                <?php endif; ?>
				
				<p><?=parse_content($CurrentBlogPost["article"])?> </p>
				
				

				
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
