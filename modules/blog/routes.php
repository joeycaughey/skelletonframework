<?php

$routes->set_template("_templates/frontend/index.tpl");  // Define template								
$routes->add_route("module_blog_url", "/blog/", "modules/blog/frontend/index");
$routes->add_route("module_blog_view_url", "/blog/view/:id/", "modules/blog/frontend/view", array("id" => "[0-9]+"));

$routes->set_template("_templates/blank.tpl");  // Define template	
$routes->add_route("blog_rss_feed_url", "/blog/rss/", "modules/blog/frontend/rss");


/********** SKELETON ADMIN ROUTES *********/
$routes->set_template("modules/cms/_templates/default/index.tpl");
$routes->add_route("module_blog_cms_url", "/cms/blog/", "modules/blog/cms/index");
$routes->add_route("module_blog_cms_add_url", "/cms/blog/add/", "modules/blog/cms/modify");
$routes->add_route("module_blog_cms_edit_url", "/cms/blog/edit/:id/", "modules/blog/cms/modify", array("id" => "[0-9]+"));





