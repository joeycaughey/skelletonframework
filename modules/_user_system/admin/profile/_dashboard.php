<?php 
global $ContactsModel;
global $UsersModel;
global $ModuleContestantsModel;
global $ModuleContestContestantsModel;
global $ModuleContestsModel;
global $ModuleSigFigsModel;
global $ImagesModel;
global $ModuleCommentsModel;

//include("site/profile/_auth.php");


//$ActiveContests = $ModuleContestantsModel->contests($Contestant["id"], true, "AND (status = 'Active' OR status = 'Featured')")

$ActiveContests = array();
?>
<div class="group">
	<div class="header">
		<h4>Active Contest</h4>
	</div>
	<div class="content <?php if ($ActiveContests) : ?>nopadding<?php endif;?>">
	
		<?php if ($ActiveContests) : ?>
			<?php foreach($ActiveContests as $contest) : ?>
			<div class="three-column-offset-left-layout intro">
				<div class="column first">
					<div class="photo">
						<a href="javascript: load_contest_resume(<?=$contest["id"]?>);">
							<?php if ($contest["image_id"]) : ?>
								<img src="<?=$ImagesModel->get_url($contest["image_id"], "medium")?>" width="100%" border="0">
							<?php endif; ?>
						</a>
					</div>
				</div>
				<div class="column middle">	
					<div class="into">
						<h3><a href="javascript: load_contest_resume(<?=$contest["id"]?>);"><?=$contest["name"]?></a></h3>
						<h4 class="active"><?=$ModuleSigFigsModel->display($contest["sigfig_id"])?></h4>
					
						<?php if ($ModuleContestsModel->is_running($contest["id"])) : ?>
							<div class="number-block">
								<p><?= $ModuleContestsModel->days_remaining($contest["id"])?></p>
								<div>Days<br />Remaining</div>
							</div>
							<div class="number-block">
								<p><?= $ModuleContestsModel->rising_stars($contest["id"])?></p>
								<div>Rising<br />Stars</div>
							</div>
							<div style="clear: both;"></div>	
						<?php else : ?>
						<br />
							<div class="percentage-bar small">
								<p><?=$ModuleContestsModel->percentage($contest["id"])?>%&nbsp;&nbsp;&nbsp;</p> <div><div style="width: <?=$ModuleContestsModel->percentage($contest["id"])?>% !important;"></div></div>
							</div>
						
						<?php endif; ?>
					</div>
				</div>
				<div class="column">
					<table cellspacing="0" cellpadding="0" width="100%" class="rank">
						<tr>
							<td rowspan="2" class="number">
								<div><?= $ModuleContestContestantsModel->contest_rank($contest["id"], $Contestant["id"]) ?></div>
								<small>Contest Rank</small>
							</td>
							<td class="like"><a href="javascript: void(0);">Like</a></td>
						</tr>
						<tr>
							<td class="dislike"><a href="javascript: void(0);">Dislike</a></td>
						</tr>
					</table>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php endforeach; ?>
		<?php else :?>
			<p>Not currently not in any contests</p>
		<?php endif; ?>
	</div>
</div>


<?= $ModuleCommentsModel->display("contestant_comments", $Contestant["id"])?>