<?php
global $NavigationGroupsModel;
?>
<h1>Other Sections:</h1>
<?= $NavigationGroupsModel->build(2);?>
<br />
<?php
global $WidgetsModel;
$Widgets = $WidgetsModel->find("WHERE default_widget='Yes' ORDER BY id", true);

$selected_widgets = ($section["widgets"])
	? $section["widgets"] : array();

if (count($selected_widgets)==0) {
	include("modules/news/frontend/partials/list.php");
	foreach ($Widgets as $widget) {	
		$file = "site/_types/_widget.php";
		if (file_exists($file)) include($file); 
	}
} else {
	foreach($selected_widgets as $widget) {
		include($widget["value"]);
	}
}
?>