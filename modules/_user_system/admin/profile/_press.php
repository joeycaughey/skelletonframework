<?php 
global $ModuleContestantsModel;
global $ModuleContestantPressLinksModel;
global $ModuleCommentsModel;

include("site/profile/_auth.php");

if ($authenticated) {
	if ($_POST) {
		
		if ($_POST["action"]) {
			$ModuleContestantPressLinksModel->delete("WHERE id = '{$_POST["id"]}'");
		} else {
		//if (VALID_url($_POST["link"])) {
			$ModuleContestantPressLinksModel->insert(array(
				"contestant_id" => $ModuleContestantsModel->ACTIVE_CONTESTANT["id"], 
				"link" => $_POST["link"]
			));
		//}
		}
	}
}

$Links = $ModuleContestantPressLinksModel->find("WHERE contestant_id = '{$Contestant["id"]}'", true);

?>
<div class="group">
	<div class="header">
		<h4>Press Links</h4>	
	</div>
	<div class="content">
	
		<?php if ($authenticated) : ?>
		<form method="POST" method="post">
			<input type="text" name="link" style="width: 80%;" maxlength="255" />
			<button type="button" id="add-link-button"">Add Link</button>
			<div class="hr"></div>
		</form>
		<?php endif; ?>
		
		<?php if (!$Links) : ?>
			<p>Currently no links.</p>
		<?php else : ?>
			<ul class="standard">
				<?php foreach($Links as $link) : ?>
				<li>[<a href="javascript: remove_link(<?=$link["id"]?>)">Remove</a>] <a href="<?=$link["link"]?>" target="_blank"><?=$link["link"]?></a></li>
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
	$("#add-link-button").click(function() {	
		$.post("<?=get_uri("profile_partial_url", array("partial" => "_press")) ?>", {
			link: $("INPUT[name=link]").val()
		}, function(html) { 
			$("#profile-content").html(html);
		});	
	});
});

function remove_link(id) {
	$.post("<?=get_uri("profile_partial_url", array("partial" => "_press")) ?>", {
		action: 'delete',
		id: id
	}, function(html) { 
		$("#profile-content").html(html);
	});	
}

</script>