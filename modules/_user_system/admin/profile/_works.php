<?php 
global $ModuleScollarsModel;
global $ModuleWorksModel;
global $ModuleCommentsModel;

include("modules/_user_system/admin/profile/_auth.php");

if ($authenticated) {
	if ($_POST) {
		
		if ($_POST["action"]) {
			$ModuleWorksModel->delete("WHERE id = '{$_POST["id"]}'");
		} else {
		//if (VALID_url($_POST["link"])) {
			$ModuleWorksModel->insert(array(
				"scollar_id" => $ModuleScollarsModel->ACTIVE_SCOLLAR["id"], 
				"link" => $_POST["link"]
			));
		//}
		}
	}
}

$Works = $ModuleWorksModel->find("WHERE scollar_id = '{$Scollar["id"]}'", true);

?>
<div class="group">
	<div class="header">
		<h4>Your Current Works</h4>	
	</div>
	<div class="content">
	
		<?php if ($authenticated) : ?>
		<form method="POST" method="post">
			<input type="text" name="link" style="width: 80%;" maxlength="255" />
			<button type="button" id="add-link-button"">Add Link</button>
			<div class="hr"></div>
		</form>
		<?php endif; ?>
		
		<?php if (!$Works) : ?>
			<p>Currently no works.</p>
		<?php else : ?>
			<ul class="standard">
				<?php foreach($Works as $work) : ?>
				<li>[<a href="javascript: remove_link(<?=$work["id"]?>)">Remove</a>] <a href="<?=$work["link"]?>" target="_blank"><?=$work["link"]?></a></li>
				<?php endforeach; ?>
			</ul>
			<div style="clear: both;"></div>
		<?php endif; ?>
		
		
	</div>
	<div style="clear: both;"></div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$("#add-link-button").click(function() {	
		$.post("<?=get_uri("profile_partial_url", array("partial" => "_works")) ?>", {
			link: $("INPUT[name=link]").val()
		}, function(html) { 
			$("#profile-content").html(html);
		});	
	});
});

function remove_link(id) {
	$.post("<?=get_uri("profile_partial_url", array("partial" => "_works")) ?>", {
		action: 'delete',
		id: id
	}, function(html) { 
		$("#profile-content").html(html);
	});	
}

</script>