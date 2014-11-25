<?php
class CacheXMLModel {
	private $file;
	
	function CacheXMLModel($file) {
		$this->file = $file; 
	}
	
	function exists() {
		return (file_exists($this->file) && filesize($this->file)>0) ? true : false;
	}
	
	function create($remote_url) {
		//echo $remote_url." \n";
		$data = file_get_contents($remote_url);
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
	
	function load($remote_url) {
		
		try {
			if ($this->exists()) {
				//echo "\nLoading... ".$this->file;
				return simplexml_load_string($this->get());
			} else {
				return simplexml_load_string($this->create($remote_url));
			}
		} catch (Exception $e) {
			$subject = "XML failure {$CONFIG["site"]["name"]}.";
			$message = $e;
			send_notification_message($CONFIG["email"], $subject, $message);
		}
		
	} 
}