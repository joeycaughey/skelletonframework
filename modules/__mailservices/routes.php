<?php
/********** SKELETON FRONT END ROUTES *********/


$routes->set_template("_templates/blank.tpl");
$routes->add_route("admin_module_mailinglists_signup_url", "/mailinglist/signup/", "modules/__mailservices/frontend/signup");
$routes->add_route("admin_module_mailinglists_save_url", "/mailinglist/save/", "modules/__mailservices/frontend/save");
$routes->add_route("admin_module_mailinglists_export_url", "/admin/mailinglists/export/:id/", "modules/__mailservices/admin/mailinglists.export", array("id" => "[0-9]+"));
$routes->add_route("compose_url", "/admin/:user_type/messaging/compose/", "modules/__mailservices/admin/_compose.ajax", array("user_type" => "[a-zA-Z1-9-_]+"));



$routes->add_route("module_newsletters_url", "/newsletters/", "modules/__mailservices/frontend/newsletters");


/********** SKELETON ADMIN ROUTES *********/
$routes->set_template("modules/cms/_templates/default/index.tpl");  // Define template


$routes->add_route("admin_module_mailinglists_settings_url", "/cms/mailinglists/settings/", "modules/__mailservices/cms/settings");

$routes->add_route("admin_module_mailinglists_emails_url", "/cms/manage/emails/", "modules/__mailservices/cms/emails/index");
$routes->add_route("admin_module_mailinglists_emails_add_url", "/cms/manage/emails/add/", "modules/__mailservices/cms/emails/modify");
$routes->add_route("admin_module_mailinglists_emails_edit_url", "/cms/manage/emails/edit/:id/", "modules/__mailservices/cms/emails/modify", array("id" => "[0-9]+"));


$routes->set_template("_templates/admin/index.tpl");
$routes->add_route("messaging_url", "/admin/:user_type/messaging/", "modules/__mailservices/admin/index", array("user_type" => "[a-zA-Z1-9-_]+"));
$routes->add_route("messaging_view_url", "/admin/:user_type/messaging/view/:id/", "modules/__mailservices/admin/view", array("user_type" => "[a-zA-Z1-9-_]+", "id" => "[0-9]+"));


$routes->set_template("modules/cms/_templates/default/index.tpl");  // Define template
$routes->add_route("admin_module_newsletters_url", "/cms/newsletters/", "modules/__mailservices/cms/newsletters/index");
$routes->add_route("admin_module_newsletter_add_url", "/cms/newsletters/add/", "modules/__mailservices/cms/newsletters/modify");
$routes->add_route("admin_module_newsletter_edit_url", "/cms/newsletters/edit/:id/", "modules/__mailservices/cms/newsletters/modify", array("id" => "[0-9]+"));




$routes->add_route("module_mailinglists_cms_url", "/cms/mailinglists/", "modules/__mailservices/cms/lists/index");
$routes->add_route("admin_module_mailinglists_list_url", "/cms/mailinglists/list/", "modules/__mailservices/cms/mailinglists");
$routes->add_route("admin_module_mailinglists_add_url", "/cms/mailinglists/add/", "modules/__mailservices/cms/mailinglists.manage");
$routes->add_route("admin_module_mailinglists_edit_url", "/cms/mailinglists/edit/:id/", "modules/__mailservices/cms/mailinglists.manage", array("id" => "[0-9]+"));
$routes->add_route("admin_module_mailinglists_delete_url", "/cms/mailinglists/delete/:id/", "modules/__mailservices/cms/mailinglists.delete", array("id" => "[0-9]+"));

$routes->add_route("admin_module_mailinglists_contacts_url", "/cms/mailinglist/:mailinglist/contacts/", "modules/__mailservices/cms/contacts", array("mailinglist" => "[a-zA-Z0-9_-]+"));
$routes->add_route("admin_module_mailinglists_contact_add_url", "/cms/mailinglist/:mailinglist/contact/add/", "modules/__mailservices/cms/contact.manage", array("mailinglist" => "[a-zA-Z0-9_-]+"));
$routes->add_route("admin_module_mailinglists_contact_edit_url", "/cms/mailinglist/:mailinglist/contact/edit/:id/", "modules/__mailservices/cms/contact.manage", array("mailinglist" => "[a-zA-Z0-9_-]+", "id" => "[0-9]+"));
$routes->add_route("admin_module_mailinglists_contact_delete_url", "/cms/mailinglist/:mailinglist/contact/delete/:id/", "modules/__mailservices/cms/contact.delete", array("mailinglist" => "[a-zA-Z0-9_-]+", "id" => "[0-9]+"));
