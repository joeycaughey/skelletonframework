<?PHP
global $UserGroupsModel;

$UserGroupsModel->insert(array("name" => "cmsadministrator", "description" => "CMS Administrator", "login_url" => "/cms/", "order_id" => 10));
$UserGroupsModel->insert(array("name" => "user", "description" => "User", "login_url" => "/account/", "order_id" => 70));
