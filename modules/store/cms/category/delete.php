<?php
global $sitename;
global $ProductionsGroupsModel;
$ProductionsGroupsModel->delete(str2int($_GET["id"]));
header("Location: ".get_uri("admin_module_productions_url"));
