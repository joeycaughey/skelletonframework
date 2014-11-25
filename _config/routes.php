<?php
$routes = new Routes();

$routes->set_template("_templates/frontend/index.tpl");
$routes->add_route("home_url", "/", "site/home");

