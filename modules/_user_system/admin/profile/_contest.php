<?php 
global $ContactsModel;
global $UsersModel;
global $ModuleContestantsModel;
global $ModuleContestContestantsModel;
global $ModuleContestBoostsModel;
global $ModuleContestantBoostsCompletedModel;
global $ModuleContestsModel;
global $ModuleSigFigsModel;
global $ImagesModel;


if (!$_GET["contest_id"]) die("You must enter a contest.");

include("site/profile/_auth.php");

$Contest = $ModuleContestsModel->find("WHERE id = '{$_GET["contest_id"]}'", false);
//$AvailableBoosts = $ModuleContestBoostsModel->available($Contest["id"], $Contestant["id"]);
$Boosts = $ModuleContestBoostsModel->find("WHERE contest_id = '{$Contest["id"]}'", true); 

?>
<style>

ul.small-photo-list {
	
}

ul.small-photo-list li {
	width: 50px;
	height: 50px;
	margin-right: 7px;
	background: #eee;
	float: left;
}
</style>
<div class="group">
	<div class="header">
		<ul>
			<li class="first"><a href="<?= get_uri("module_contests_url", array("slug" => FORMAT_forurl($Contest["name"])))?>">View Contest Page</a></li>
			<li class="last"><a href="javascript: void(0);" class="join_contest">Join Now</a></li>
		</ul>
		<h4>Active Contest: <?=$Contest["name"]?></h4>
		
	</div>
	<div class="content nopadding">
	
		<div class="three-column-offset-left-layout intro">
			<div class="column first">
				<div class="photo">
				
					<?php if ($Contest["image_id"]) : ?>
						<img src="<?=$ImagesModel->get_url($Contest["image_id"], "medium")?>" width="100%" border="0">
					<?php endif; ?>
				</div>
			</div>
			<div class="column middle">	
				<div class="into">
					<h3><a href="<?= get_uri("module_contests_url", array("slug" => FORMAT_forurl($Contest["name"]))) ?>"><?=$Contest["name"]?></a></h3>
					<h4 class="active"><?=$ModuleSigFigsModel->display($Contest["sigfig_id"])?></h4>
				
					<?php if ($ModuleContestsModel->is_running($Contest["id"])) : ?>
						<div class="number-block">
							<p><?= $ModuleContestsModel->days_remaining($Contest["id"])?></p>
							<div>Days<br />Remaining</div>
						</div>
						<div class="number-block">
							<p><?= $ModuleContestsModel->rising_stars($Contest["id"])?></p>
							<div>Rising<br />Stars</div>
						</div>
						<div style="clear: both;"></div>	
					<?php else : ?>
						<br />
						<div class="percentage-bar small">
							<p><?=$ModuleContestsModel->percentage($Contest["id"])?>%&nbsp;&nbsp;&nbsp;</p> <div><div style="width: <?=$ModuleContestsModel->percentage($contest["id"])?>%"></div></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="column">
				<table cellspacing="0" cellpadding="0" width="100%" class="rank">
					<tr>
						<td rowspan="2" class="number">
							<div><?= $ModuleContestContestantsModel->contest_rank($Contest["id"], $Contastant["id"]) ?></div>
							<small>Contest Rank</small>
						</td>
						<td class="like"><a href="javascript: void(0);" class="like" name="<?=$Contestant["id"]?>">Like</a></td>
					</tr>
					<tr>
						<td class="dislike"><a href="javascript: void(0);" class="dislike" name="<?=$Contestant["id"]?>">Dislike</a></td>
					</tr>
				</table>
			</div>
			<div style="clear: both;"></div>
		</div>
		
		
		<div style="padding: 15px;">
			<h2>Your Contest Resume</h2>
			<div class="two-column-layout">
				<div class="column first">
					<h4>Contest Specific Game Tape:</h4>
					<iframe src="http://player.vimeo.com/video/<?= ($Contest["vimeo_video_id"]) ? $Contest["vimeo_video_id"] : "15888399" ?>" width="290" height="175" frameborder="0"></iframe>
					<div class="hr"></div>
					<h4>Contest Photos:</h4>
					<div id="photos_holder"></div>
					<div style="clear: both;"></div>	
					
					<div class="hr"></div>
					<h4>Fans:</h4>
					<div id="fans_holder"></div>
					<div style="clear: both;"></div>
					
				</div>
				<div class="column">
					<br />
					<div class="hr"></div>
					
					<h4>Press:</h4>
					<div id="press_holder">
					
					
					</div>
					<div class="hr"></div>
					
					<h4>Available Boosts:</h4>
					<ul class="standard">
						<?php if (!$Boosts): ?>
							<li>There are no available boosts.</li>
						<?php else :  $display_alert = true; ?>
							<?php foreach($Boosts as $boost) : ?>
								<?php if ($ModuleContestsModel->is_running($Contest["id"]) && $authenticated) : ?>
									<?php if ($ModuleContestantBoostsCompletedModel->is_completed($Contest["id"], $boost["id"], $contestant_id)) : ?>
										<li class="crossout"><?=$boost["description"]?> <b>(<?=$boost["points"]?>pts)</b></li>
									<?php else : ?>
										<li><a href="javascript: boost_popup('<?=$boost["boost_resource"]?>', <?=$boost["id"]?>, { })"><?=$boost["description"]?> <b>(<?=$boost["points"]?>pts)</b></a></li>
									<?php endif; ?>
								<?php else : ?>
									<li><?=$boost["description"]?> <b>(<?=$boost["points"]?>pts)</b></li>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<div class="hr"></div>
					<h4>Available Boosts:</h4>
					<p class="first">Your Pitch</p>
					
					
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>

<?php include("modules/comments/frontend/_partial.php") ?>

<script type="text/javascript">
$(document).ready(function() {
	$.post('<?= get_uri("ajax_contest_photos_url") ?>', { 
		contestant_id: <?= $Contestant["id"] ?>,
		contest_id: <?= $Contest["id"] ?>
	}, function(html) { 
		$("#photos_holder").html(html); 
	});	

	
	$.post('<?= get_uri("ajax_fans_url") ?>', { 
		contestant_id: <?= $Contestant["id"] ?>
	}, function(html) { 
		$("#fans_holder").html(html); 
	});	

	$("a.like").click(function() {
		$.post('<?= get_uri("ajax_fans_url") ?>', { 
				contestant_id: $(this).attr("name"),
				op: 'add'
			}, function(html) { 
				$("#fans_holder").html(html); 
		});
	});

	$("a.dislike").click(function() {
		$.post('<?= get_uri("ajax_fans_url") ?>', { 
				contestant_id: $(this).attr("name"),
				op: 'remove'
			}, function(html) { 
				$("#fans_holder").html(html); 
		});
	});

	$("a.join_contest").click(function(e) {
		e.preventDefault();
		obj = $(this);
		$.post("<?= get_uri("profile_op_url") ?>", {
			action: "join",
			contest_id: $(this).attr("name")
		}, function() {
			obj.fadeOut(1000);
			obj.text("Joined");
			obj.fadeIn(1000);
			obj.attr("href", "javascript: void(0);");
		});
	});
});

</script>