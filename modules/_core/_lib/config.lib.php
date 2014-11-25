<?php
// v2.0
//****************************************************
// Config Class Library
// Last Modified: Oct 2008
//****************************************************

function config($name, $value = false) {
	global $CONFIG;
	$result = DBQUERY($CONFIG["site"], "SELECT value FROM tbl_core_config WHERE name = '".$name."'");
	
	if ($value) {
		$v["name"] = $name;
		$v["value"] = $value;
		if (mysql_num_rows($result)==0) {
			mySQL_insert($CONFIG["site"], "tbl_core_config", $v);
		} else {
			$dump = mysql_fetch_assoc($result);
			mySQL_update($CONFIG["site"], "tbl_core_config", $v, $dump["id"]);
		}
		return true;
	} else {
		$dump = mysql_fetch_assoc($result);
		return $dump["value"];
	}
}

function set_config($name, $value) {
	global $CONFIG;
	$result = DBQUERY($CONFIG["site"], "SELECT id FROM tbl_core_config WHERE name = '".$name."'");
	
	$v["name"] = $name;
	$v["value"] = $value;
	if (mysql_num_rows($result)==0) {
		mySQL_insert($CONFIG["site"], "tbl_main_config", $v);
	} else {
		$dump = mysql_fetch_assoc($result);
		mySQL_update($CONFIG["site"], "tbl_main_config", $v, $dump["id"]);
	}

	return true;
}

function get_selected_config($names = array()) {
	global $CONFIG;
	$config = array();
	
	foreach ($names as $name) {
		$result = DBQUERY($CONFIG["site"], "SELECT value FROM tbl_core_config WHERE name = '".$name."'");
		$dump = mysql_fetch_assoc($result);
		$config[$name] = $dump["value"];
	}
	return $config;
}
