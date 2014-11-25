<?php
$ModuleScollarsModel->ACTIVE_SCOLLAR();

if ($_GET["scollar_id"]) {
	$Scollar = $ModuleScollarsModel->find("WHERE id = '{$_GET["scollar_id"]}'", false);
} else {
	if (!$ModuleScollarsModel->ACTIVE_SCOLLAR()) {
		header("Location: ".get_uri("user_login_url"));
	}
	$Scollar = $ModuleScollarsModel->ACTIVE_SCOLLAR;
}
$authenticated = ($ModuleScollarsModel->ACTIVE_SCOLLAR["id"]==$Scollar["id"]) ? true : false;
