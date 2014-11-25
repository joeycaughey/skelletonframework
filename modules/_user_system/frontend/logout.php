<?php
global $sitename;
$_SESSION[$sitename]["user"]["authentication"] = array("authenticated" => false, "id" => false);
header("Location: ".get_uri("home_url"));