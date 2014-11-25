<?php
global $ModuleFaqModel;
global $ModuleMailServicesNewslettersModel;
global $ImagesModel;
global $FilesModel;

$Newsletters = $ModuleMailServicesNewslettersModel->find("WHERE id = id ORDER BY date_added", true);
?>
<div class="two-column-layout">
	<div class="column first">
	<p><?=config("newsletter_content")?></p>
	<?php if (count($Newsletters)==0) : ?>
		<p>There are currently no newsletters.</p>
	<?php else : ?>
		<ul class="newsletters">
			<?php foreach($Newsletters as $newsletter) : ?>
			<li>
				<?= date("M, d Y", $newsletter["date_added"]) ?> - 
				<a href="<?= $FilesModel->get_url($newsletter["file_id"])?>">
					<?=$newsletter["title"]?>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
</div>


