<?php
$ImagesModel = new ImagesModel();

class ImagesModel  {
	public $images = array();
	
	public $resource;
	public $resource_id;
	public $resource_type = "image";
	public $sizes;
	
	function resource($resource, $resource_id, $group = false, $sizes = false) {
		global $FilesModel;
		$this->resource = $resource;
		$this->resource_id = $resource_id;
		$this->group = $group;
		$this->sizes  = $sizes;
		
		if(!$this->sizes) {
			$this->sizes = array(
				"thumb" => array("width" => 100, "height" => 75, "crop" => false),
				"medium" => array("width" => 250, "height" => 250, "crop" => false)
			);
		} 
	
		$this->images = $FilesModel->resource($this->resource, $this->resource_id, $this->resource_type, $this->group);	
		return $this->images;
	}
	
	function add($filedata, $parameters = array()) {
		global $FilesModel;
		global $DirectoryModel;
		
		if (!$this->resource || !$this->resource_id || !$this->resource_type) return false;
		
		$v = $parameters;
		$FilesModel->resource($this->resource, $this->resource_id, $this->resource_type, $this->group, $this->sizes);
		
		if ($parameters["directory_id"]) $FilesModel->directory_id = $parameters["directory_id"];
		
		if ($filedata["filename"]) {
			$FilesModel->add($filedata, $parameters["title"], $parameters["description"]);
			return true;
		} else {
			foreach ($filedata as $file) {
				$FilesModel->add($file, $file["title"], $file["description"]);
			}
			return true;
		} 
		return false;	
	}
	
	function upload_single_file($directory_id, $filedata, $parameters = array()) {
		global $FilesModel;
		global $DirectoryModel;
		
		$v = $parameters;
		
		$FilesModel->resource($this->resource, $this->resource_id, $this->resource_type, $this->group, $this->sizes);
		if ($directory_id) $FilesModel->directory_id = $directory_id;
		
		if($image_id = $FilesModel->add($filedata, $parameters["name"], $parameters["description"])) {
			return $image_id;
		}
		return false;	
	}
	
	
	function get($id) {
		global $CONFIG;
		$images_result = DBQUERY($CONFIG["site"], "SELECT * FROM tbl_main_files WHERE id = '$id'");
		
		return (mysql_num_rows($images_result)==0) ? false : mysql_fetch_assoc($images_result);
	}
	
	function get_url($id, $size=false, $none = false) {
		global $FilesModel;
		global $DirectoryModel;
		
		$File = $FilesModel->find("WHERE id = '{$id}'");
		$Directory = $DirectoryModel->find("WHERE id = '{$File["directory_id"]}'");
		
		if ($size) $display=$size."_";
		
		$file = "files/".$Directory["directory"].$display.$File["filename"];
		
		if (!file_exists($file) && $none) {
			return $none;
		}
		
		return "/".$file;
	}	
	
	function delete($id) {
		global $CONFIG;
		global $FilesModel;
		global $DirectoryModel;
		
		$File = $FilesModel->find(str2int($id));
		$Directory = $DirectoryModel->find(str2int($File["directory_id"]));
		
		unlink($CONFIG["uploads_dir"].$Directory["directory"].$display.$File["filename"]);
		unlink($CONFIG["uploads_dir"].$Directory["directory"].$display."thumb_".$File["filename"]);
		unlink($CONFIG["uploads_dir"].$Directory["directory"].$display."medium_".$File["filename"]);
		$FilesModel->delete(str2int($id));
		
	}	
}

function featured_image($table, $id, $image_id = false) {
	global $CONFIG;
	$result = DBQUERY($CONFIG["site"], "SELECT image_id FROM $table WHERE id = '".$_GET["id"]."'");
	$dump = mysql_fetch_assoc($result);
	
	if (!$dump["image_id"] && $image_id)  {
		DBQUERY($CONFIG["site"], "UPDATE $table SET image_id = '".$image_id."' WHERE id = '".$_GET["id"]."'");
		$dump["image_id"] = $image_id;
	} 
	return $dump["image_id"];
}

