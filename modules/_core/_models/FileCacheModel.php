<?php
class FileCacheModel {
	private $file;
	
	function FileCacheModel($file) {
		$this->file = $file; 
	}
	
	function exists() {
		return (file_exists($this->file) && filesize($this->file)>0) ? true : false;
	}
	
	function create($remote_url, $params = array()) {	
		if ($params["accept"]) {
			$opts = array(
			 'http'=> array(
			    'method'=>"GET",
			    "header" => "Accept: {$params["accept"]}\r\n" .   
						    "Content-Type: {$params["accept"]}; charset=utf-8"         
			  ),
			  'https'=> array(
			    'method'=>"GET",
			    "header" => "Accept: {$params["accept"]}\r\n" .   
						    "Content-Type: {$params["accept"]}; charset=utf-8"         
			  )
			);
	
			$context = stream_context_create($opts);
			$data = file_get_contents($remote_url, false, $context);
		} else {
			$data = file_get_contents($remote_url);
		}
		$fp = fopen($this->file, "w");
		fwrite($fp, $data);
		fclose($fp);
		return $data;
	}
	
	function get() {
		$fp = fopen($this->file, "r");
		$data = fread($fp, filesize($this->file));
		fclose($fp);
		return $data;
	}
	
	function load($remote_url, $params = array()) {
		
		try {
			if ($this->exists()) {
				//echo "\nLoading... ".$this->file;
				return $this->get();
			} else {
				return $this->create($remote_url, $params);
			}
		} catch (Exception $e) {
			$subject = "XML failure {$CONFIG["site"]["name"]}.";
			$message = $e;
			send_notification_message($CONFIG["email"], $subject, $message);
		}
		
	} 
}