<?php
global $ConfigModel;

$ConfigModel->insert(array("name" => "editMode", "value" => "wizard"));
$ConfigModel->insert(array("name" => "enableTooltips", "value" => true));

$ConfigModel->insert(array("name" => "thumbSize", "value" => false));
$ConfigModel->insert(array("name" => "medSize", "value" => false));

$ConfigModel->insert(array("name" => "thumbWidth", "value" => 100));
$ConfigModel->insert(array("name" => "thumbHeight", "value" => 100));
$ConfigModel->insert(array("name" => "medWidth", "value" => 200));
$ConfigModel->insert(array("name" => "medHeight", "value" => 200));

$ConfigModel->insert(array("name" => "numberOfNavs", "value" => 2));
$ConfigModel->insert(array("name" => "numberOfCols", "value" => 2));


$ConfigModel->insert(array("name" => "siteName", "value" => "Full CMS Site"));
$ConfigModel->insert(array("name" => "keywords", "value" => false));
$ConfigModel->insert(array("name" => "description", "value" => false));
$ConfigModel->insert(array("name" => "copyright", "value" => false));
