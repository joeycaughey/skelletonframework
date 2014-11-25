<?php
global $ModuleScollarsModel;
	
$ModuleScollarsModel->ACTIVE_SCOLLAR();

$form = new FormHelper();
$form->legend("Your Settings");
$form->input("name", array("label" => "Name", "required" => VALID_notnull));
$form->date("birthday", array("label" => "Birthday", "required" => VALID_notnull));
$form->input("hometown", array("label" => "Hometown", "required" => VALID_notnull));
$form->input("current_city", array("label" => "Current City", "required" => VALID_notnull));
$form->button("Save");


$form->data = $ModuleScollarsModel->ACTIVE_SCOLLAR;
?>


<?= $form->render() ?>
