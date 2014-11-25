<?php
global $ModuleScollarsModel;
global $ImagesModel;
global $ContactsModel;

if ($_GET["slug"]) {
	$Scollar = $ModuleScollarsModel->find_by_slug("name", $_GET["slug"]);
} else {
	if (!$ModuleScollarsModel->ACTIVE_SCOLLAR()) {
		header("Location: ".get_uri("user_login_url"));
	}
	$Scollar = $ModuleScollarsModel->ACTIVE_SCOLLAR;
}

//print_r($Scollar);

$authenticated = ($ModuleScollarsModel->ACTIVE_SCOLLAR["id"]==$Scollar["id"]) ? true : false;


if ($authenticated) {
	$sizes = array(
		"thumb" => array("width" => 50, "height" => 50),
		"medium" => array("width" => 125, "height" => 100),
		"large" => array("width" => 250, "height" => 200)
	);
	
	if ($_FILES["photo"]) {
		$ImagesModel->resource("user_profile_photo", $Scollar["id"], false, $sizes);
		if ($ModuleScollarsModel->ACTIVE_SCOLLAR["image_id"]) {
			$FilesModel->delete(str2int($Scollar["image_id"]));
		}
		$Scollar["image_id"] = $ImagesModel->upload_single_file(false, $_FILES["photo"], $_POST);
		$ModuleScollarsModel->single_update("image_id", $Scollar["image_id"], "WHERE id = '{$Scollar["id"]}'");
	} else if ($_FILES["photobucket"]) {
		$ImagesModel->resource("photobucket_photos", $Scollar["id"], false, $sizes);
		$ImagesModel->upload_single_file(false, $_FILES["photobucket"], $_POST);
		$profile_holder = "_photobucket";
	}
	
	$ImagesModel->resource("user_profile_photo", $Scollar["id"], false, $sizes);
}
?>



<div class="two-column-offset-left-layout" id="profile-bio">
	<div class="column first">
		<div class="group dark">
			<div class="content nopadding">
				<div style="padding: 10px;">
					<div class="photo">
						<a name="photo-anchor"></a>
						<?php if ($Scollar["image_id"]) : ?>
							<img src="<?=$ImagesModel->get_url($Scollar["image_id"], "large")?>" width="100%" border="0">
							<?php if ($authenticated) : ?>
								<a href="javascript: void(0);" id="upload-profile-photo-button" style="text-transform: uppercase;">Update Photo</a>
							<?php endif; ?>
						<?php elseif ($authenticated) : ?>
							<p><a href="javascript: void(0);" id="upload-profile-photo-button">UPLOAD PROFILE PHOTO</a></p>
						<?php endif; ?>
						<div id="profile_photo_upload_holder" style="display: none; margin-bottom: 15px;">
							<form method="POST" method="post" enctype="multipart/form-data">
								<input type="file" name="photo" style="width: 60%;"/><button>Upload</button>
							</form>
						</div>
						<div style="clear: both;"></div>
					</div>
					
				</div>

				<div class="blurb">
					<h4>MY THOUGHTS...</h4>
					
					
					<div id="bio_holder">
						<p class="first"><?=$Scollar["bio"]?>
						<br />
						<?php if ($authenticated) :?>[<a href="javascript: void(0);" id="update-bio-button">UPDATE</a>] <?php endif; ?>
						</p>	
						
					</div>
					<div id="bio_edit_holder" style="display:none;">
						<textarea name="bio" style="width: 95%; height: 70px;" maxlength="140"><?=stripslashes($Scollar["bio"])?></textarea>
						<button type="button">SAVE</button>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="group">
			<div class="header">
				<h4>Your Courses</h4>
			</div>
			<ul  class="content nopadding">
				<li><a href="">Class 1 - AOD120</a></li>
				<li><a href="">Class 1 - AOD120</a></li>
				<li><a href="">Class 1 - AOD120</a></li>
				<li><a href="">[+ add a course]</a></li>
			</ul>
		</div>
		
		<?php if (false) : ?>
		<div class="group">
			<div class="header">
				<h4>Goto</h4>
			</div>
			<ul id="profile-tabs" class="content nopadding">
				<li><span class="dashboard"></span><a href="javascript: void(0);">Dashboard</a></li>
				<li><span class="edit"></span><a href="javascript: void(0);">Edit</a></li>
				
				
				<li><span class="gametape"></span><a href="javascript: void(0);">Game Tape</a></li>
				<li><span class="photobucket"></span><a href="javascript: void(0);">Photo Bucket</a></li>
				<li><span class="press"></span><a href="javascript: void(0);">Press</a></li>
				<li><span class="socialcapital"></span><a href="javascript: void(0);">Social Capital</a></li>
				
			</ul>
			<div style="clear: both"></div>
		</div>
		<?php endif; ?>
			
		<?php if ($CurrentContests) : ?>
		<div class="group">
			<div class="header">
				<a href="javascript: void(0);" class="toggle">Toggle</a>
				<h4>Current Contests</h4>
			</div>
			<ul class="content nopadding">
				<?php foreach($CurrentContests as $contest) : ?>
				<li><a href="javascript: load_contest_resume('<?= $contest["id"]?>');"><?= $contest["name"]?></a> | Rank <?= $ModuleContestContestantsModel->contest_rank($contest["id"], $ModuleContestantsModel->ACTIVE_CONTESTANT["id"]) ?></li>
				<?php endforeach; ?>
			</ul>
			<div style="clear: both"></div>
		</div>
		<?php endif; ?>


		<?php if ($PastContests) : ?>
		<div class="group ">
			<div class="header">
				<a href="javascript: void(0);" class="toggle">Toggle</a>
				<h4>Past Contests</h4>
			</div>
			<ul class="content nopadding">
				<?php foreach($PastContests as $contest) : ?>
				<li><a href="javascript: load_contest_resume('<?= $contest["id"]?>');"><?= $contest["name"]?></a> | Rank <?= $ModuleContestContestantsModel->contest_rank($contest["id"], $ModuleContestantsModel->ACTIVE_CONTESTANT["id"]) ?></li>
				<?php endforeach; ?>
			</ul>
			<div style="clear: both"></div>
		</div>		
		<?php endif; ?>
		
	</div>
	<div class="column">
		<a name="location-anchor"></a>
	
		<div class="text-doubleup float-right">
			
			<p style="width:260px; line-height: 12px; "><?= $ContactsModel->display($Scollar["user_id"])?></p>
			<div>
				<b>Location:</b>  <? if ($authenticated) : ?>(<a href="javascript: void(0);" id="set-location-button">SET</a>)<?php endif; ?><br />
				<b>Birthdate:</b> <?= date("M d,Y", $Scollar["birthdate"]) ?> <? if ($authenticated) : ?>(<a href="javascript: void(0);" id="set-birthdate-button">SET</a>) <?php endif; ?>
			</div>
		</div>
		<div style="clear: both; height: 25px;"></div>
		<div id="profile-dropdown" style="display: none;"></div>
		<div id="profile-content">
		
		</div>
	</div>
	
	<div style="clear: both;"></div>
</div>

<script type="text/javascript">

var partial = '<?= ($_GET["partial"]) ? $_GET["partial"] : "_dashboard" ?>';
var scollar_id = '<?=$Scollar["id"]?>';
var profile_op_url = '<?= get_uri("profile_op_url") ?>';

$(document).ready(function() {
	$.get("/profile_partial/"+partial+"/", {
		scollar_id: scollar_id
	}, function(html) { 
		partial = '_'+partial;
		$("#profile-content").html(html);
	});
});

</script>
