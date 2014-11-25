<?php 
global $ModuleBlogModel;

$Archives = $ModuleBlogModel->archives()

?>
<h2>Archives<span>&nbsp;</span></h2>
			
<ul>
	<?php if (count($Archives)==0) : ?>
		<li>No Archives</li>
	<?php else :?>
		<?php foreach($Archives as $archive => $date_key) : ?>
			<li><a href=""><?=$archive?></a><span>&nbsp;</span></li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>