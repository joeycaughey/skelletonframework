<?php
// v2.0
//****************************************************
// Routes Class Library
// Last Modified: Aug 2008
//****************************************************
class Routes {
	var $routes = array();
	var $current_controller="default";
	var $urls = array();
	
	function set_controller($name) {
		$this->current_controller = $name;
	}
	
	function set_template($name) {
		$this->current_template = $name;
	}
	
	function add_route($url, $uri, $route, $params = array()) {
		$this->urls[$url] = $uri;
		$expression = $this->uri_to_regex($uri, $params);
		$this->routes[$uri] = array("route" => $route, "controller" => $this->current_controller, "template" => $this->current_template,  "params" => $params, "expression" => $expression);
	}
	
	private function uri_to_regex($uri, $params = array()) {
		//$uri = url_as_regular_expression($uri);
		$expression[0] = $uri;
		$expression[1] = $uri;
		foreach ($params as $k => $p) {
			$expression[0] = str_replace(":".$k, $p, $expression[0]);
			$expression[1] = str_replace(":".$k, "(".$p.")", $expression[1]);
		}
		return $expression;
	}
	
	function get() {
		return $this->routes;
	}
	
	function get_uri($name) {
		return $this->urls[$name];
	}
	
}

function get_uri($name, $parameters = array()) {
	global $routes;
	global $CONFIG;

	$route = $routes->get_uri($name);
	foreach ($parameters as $k => $p) {
		$route = str_replace(":".$k, $p, $route);
	}
	$route = preg_replace("/\/:[a-zA-Z_]+/", "", $route);
	$route = preg_replace("/\/:[0-9]+/", "", $route);
	return $CONFIG["site"]["facebook"]["url"].$route;
}



function is_uri($uri, $class) {
	
	if (is_array($uri)) {
		foreach ($uri as $u) {
			if ($_SERVER["REQUEST_URI"]==$u) return $class;
		}	
	} else {
		if ($_SERVER["REQUEST_URI"]==$uri) return $class;
	}
	
	return false;
}


?>