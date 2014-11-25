<?php
$routes->set_template("_templates/frontend/blank.tpl");
$routes->add_route("user_login_return_url", "/login/return/", "modules/_user_system/frontend/return");

$routes->set_template("_templates/frontend/page.tpl");
$routes->add_route("user_login_url", "/login/", "modules/_user_system/frontend/login");
$routes->add_route("forgot_password_url", "/login/forgot_password/", "modules/_user_system/frontend/forgot_password");
$routes->add_route("user_validation_url", "/admin/validation/:hash/", "modules/_user_system/frontend/validation", array("hash" => "[a-zA-Z0-9]+"));
//$routes->add_route("user_signup_url", "/signup/", "modules/_user_system/frontend/signup");
$routes->add_route("user_signup_finished_url", "/admin/finished/:hash/", "modules/_user_system/frontend/finished", array("hash" => "[a-zA-Z0-9]+"));


$routes->set_template("_templates/frontend/page.tpl");
$routes->add_route("change_password_url", "/change_password/", "modules/_user_system/frontend/change_password");
$routes->add_route("logout_url", "/logout/", "modules/_user_system/frontend/logout");
//$routes->add_route("user_profile_url", "/profile/", "site/profile/index");

$routes->set_template("_templates/admin/index.tpl");
$routes->add_route("admin_users_url", "/admin/:user_type/users/", "modules/_user_system/admin/index", array("user_type" => "[a-zA-Z]+"));
$routes->add_route("admin_users_add_url", "/admin/:user_type/users/add/", "modules/_user_system/admin/modify", array("user_type" => "[a-zA-Z]+"));
$routes->add_route("admin_users_edit_url", "/admin/:user_type/users/edit/:id/", "modules/_user_system/admin/modify", array("user_type" => "[a-zA-Z]+", "id" => "[0-9]+"));
$routes->add_route("admin_user_setup_url", "/admin/:user_type/users/setup/:hash/", "modules/_user_system/admin/setup", array("user_type" => "[a-zA-Z]+", "hash" => "[a-zA-Z0-9]+"));


$routes->set_template("modules/cms/_templates/default/index.tpl");
$routes->add_route("module_users_admin_url", "/cms/users/", "modules/_user_system/cms/index");

$routes->add_route("module_users_admin_list_url", "/cms/users/list/", "modules/_user_system/cms/users/index");
$routes->add_route("module_users_admin_add_url", "/cms/users/add/", "modules/_user_system/cms/users/modify");
$routes->add_route("module_users_admin_edit_url", "/cms/users/edit/:id/", "modules/_user_system/cms/users/modify", array("id" => "[0-9]+"));
$routes->add_route("module_users_admin_delete_url", "/cms/users/delete/:id/", "modules/_user_system/cms/users/delete", array("id" => "[0-9]+"));

$routes->add_route("module_users_admin_groups_url", "/cms/users/groups/", "modules/_user_system/cms/groups/index");
$routes->add_route("module_users_admin_groups_add_url", "/cms/users/groups/add/", "modules/_user_system/cms/groups/modify");
$routes->add_route("module_users_admin_groups_edit_url", "/cms/users/groups/edit/:id/", "modules/_user_system/cms/groups/modify", array("id" => "[0-9]+"));
$routes->add_route("module_users_admin_groups_delete_url", "/cms/users/groups/delete/:id/", "modules/_user_system/cms/groups/delete", array("id" => "[0-9]+"));


$routes->add_route("module_users_admin_access_url", "/cms/users/group/:group_id/access/", "modules/_user_system/cms/access/index", array("group_id" => "[0-9]+"));
$routes->add_route("module_users_admin_access_add_url", "/cms/users/group/:group_id/access/add/", "modules/_user_system/cms/access/modify" , array("group_id" => "[0-9]+"));
$routes->add_route("module_users_admin_access_edit_url", "/cms/users/group/:group_id/access/edit/:access_id/", "modules/_user_system/cms/access/modify", array("group_id" => "[0-9]+", "access_id" => "[0-9]+"));
$routes->add_route("module_users_admin_access_delete_url", "/cms/users/group/:group_id/access/delete/:access_id/", "modules/_user_system/cms/access/delete", array("group_id" => "[0-9]+", "access_id" => "[0-9]+"));

