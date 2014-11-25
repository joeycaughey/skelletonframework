<?php 
global $ModuleContestantsModel;
global $ModuleCommentsModel;

include("site/profile/_auth.php");

?>
<div class="group">
	<div class="header">
		<h4>Game Tape</h4>
	</div>
	<div class="content">
		<ul class="video-list">
			<li>
				<h4>Title</h4>
				<video></video>
				<p>Caption</p>
			</li>
		</ul>
	</div>
	<div style="clear: both;"></div>
</div>


<?= $ModuleCommentsModel->display("contestant_comments", $Contestant["id"])?>