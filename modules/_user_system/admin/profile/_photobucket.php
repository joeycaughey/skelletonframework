<?php 
global $ImagesModel;
global $ModuleContestantsModel;
global $ModuleCommentsModel;

include("site/profile/_auth.php");

$sizes = array(
	"thumb" => array("width" => 50, "height" => 50),
	"medium" => array("width" => 125, "height" => 100),
	"large" => array("width" => 250, "height" => 200)
);


if ($_GET["photo_id"] && $authenticated) {
	$ImagesModel->delete("WHERE id = '{$_GET["photo_id"]}'");
}

$Photos = $ImagesModel->resource("photobucket_photos", $Contestant["id"], false, $sizes);

?>
<div class="group">
	<div class="header">
		<ul>
			<?php if ($authenticated) :?>
			<li class="only"><a href="javascript: void(0);" id="photo-upload-button">Upload Photo</a></li>
			<?php endif; ?>
		</ul>
		<h4>Photo Bucket</h4>
		
	</div>
	<div class="content">
		<form method="POST" method="post" enctype="multipart/form-data" id="photo_upload_holder" style="display:none;">
			<input type="file" name="photobucket" style="width: 60%;"/><button>Upload</button>
			<div class="hr"></div>
		</form>
		
		<?php if (!$Photos) : ?>
			<p>Currently no photos.</p>
		<?php else : ?>
			<ul class="photo-list">
				<?php foreach($Photos as $photo) : ?>
				<li style="overflow: hidden;">
					<a href="javascript: void(0)" class="delete" name="<?=$photo["id"]?>">Delete</a>
					<img src="<?=$ImagesModel->get_url($photo["id"], "medium")?>" border="0" width="100%" />
				</li>
				<?php endforeach; ?>
			</ul>
			<div style="clear: both;"></div>
		<?php endif; ?>
	
	</div>
	<div style="clear: both;"></div>
</div>

<?= $ModuleCommentsModel->display("contestant_comments", $Contestant["id"])?>
<script type="text/javascript">
$(document).ready(function() {
	$("#photo-upload-button").click(function() {
		$("#photo_upload_holder").slideToggle();
	});

	$("ul.photo-list li a.delete").click(function() {
		$.get("<?=get_uri("profile_partial_url", array("partial" => "_photobucket")) ?>", {
			contestant_id: <?=$Contestant["id"]?>,
			photo_id: $(this).attr("name")
		}, function(html) { 
			partial = '_photobucket';
			$("#profile-content").html(html);
		});
	});
});
</script>