<?php
$FilesModel = new FilesModel($CONFIG["site"], "tbl_main_files");
$FilesModel->has_one("directory", "tbl_main_directories", "directory_id");

class FilesModel extends Model {
	
	private $resource;
	private $resource_id;
	public $directory_id;
	public $sizes;
	
	public $files = array();
	
	var $schema = array(
		"structure" => array(
			array("user_id", "int", 12),
			array("directory_id", "int", 12),
			array("file_name", "varchar", 255),
			array("file_type", "varchar", 40),
			array("file_size", "bigint"),
			array("description", "varchar", 255),
			array("filename", "varchar", 255),
			array("order", "bigint"),
			array("date_added", "bigint")
		)
	);
	
	function resource($resource, $resource_id = false, $resource_type = false, $group = false, $sizes = false) {
		global $DirectoryModel;
		
		$this->resource = $resource;
		$this->resource_id = $resource_id;
		$this->resource_type = $resource_type;
		$this->group = $group;
		$this->sizes = $sizes;
		$this->directory_id = $DirectoryModel->create_from_resource(array("resource" => $resource, "resource_id" => $resource_id, "resource_type" => $resource_type, "group" => $group));
		
		$this->files = $this->find("WHERE directory_id = '".$this->directory_id."'", true);
		return $this->files;
	}
	
	function add($file_data, $parameters = array()) {
		global $CONFIG;
		global $UsersModel;
		global $DirectoryModel;
		
		
		if (!$this->directory_id) return false;
		
		$Directory = $DirectoryModel->find(str2int($this->directory_id));
	
		$file = $file_data['tmp_name'];
		$file_name = clean_file_name($file_data["name"]);
		
		$error = false;
		if ((!is_uploaded_file($file) && !file_exists($file)) || ($file_data['size']==0) ) $error = '400 Bad Request';
		//if (!$error && !($size = @getimagesize($file))) $error = '409 Conflict';

		$addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);

		if ($error) {
			header('HTTP/1.0 ' . $error);
			die;
		}
		
		$Directory = $DirectoryModel->find("WHERE id = '{$this->directory_id}'");
		$location = $CONFIG["uploads_dir"].$Directory["directory"];
					
		if (is_uploaded_file($file)) {
			move_uploaded_file($file, $location.$file_name);
		} else {
			copy($file, $location.$file_name);
		}
		
		$finfo = new finfo(FILEINFO_MIME); // return mime type ala mimetype extension
	
		if (!$finfo) {
		    die("Opening fileinfo database failed");
		}

		
		echo $finfo->file($location.$file_name);
		
		$UsersModel->ACTIVE_USER();
		
		$values = array(
			"user_id" => $UsersModel->ACTIVE_USER["id"],
			"directory_id" => $this->directory_id,
			"file_name" => $file_name,
			"file_type" => $file_data["type"],
			"file_size" => $file_data['size'],
			"description" => $parameters["description"],
			"filename" => $file_data["tmp_name"] 
		);
		
		$file_id = $this->insert($values);

		return $file_id;
	}
	
	function video($file_id, $parameters) {	
		$file_name  = clean_file_name($file_data["name"]);
		if (is_uploaded_file($file)) {
			move_uploaded_file($file, $location.$file_name);
		} else {
			copy($file, $location.$file_name);
		}

		$new_file = explode(".", $file_name);
		array_pop($new_file);
		$jpg_file = implode(".",$new_file).".jpg";
		$new_file = implode(".",$new_file).".flv";
		
		// -ab 56 -ar 22050 -b 500 -r 30 
		$exec = "ffmpeg -i ".$location.$file_name." -ab 56 -ar 22050 -qscale .1 -s 520x340 ".$location.$new_file;
		if ($CONFIG["local"]) echo $exec;
		shell_exec($exec);
		
		$exec = "ffmpeg -itsoffset -4 -i ".$location.$file_name." -y -vcodec mjpeg -vframes 1 -an -f rawvideo -s 140x92 ".$location.$jpg_file;
		if ($CONFIG["local"]) echo $exec;
		shell_exec($exec);
	}
	
	function image($file_id, $parameters=array()) {
		global $CONFIG;
		
		$File = $this->find("WHERE id = '{$file_id}'", false);
		
		if(!$parameters["sizes"]) {
			$parameters["sizes"] = array(
				"thumb" => array("width" => 100, "height" => 75, "crop" => false),
				"medium" => array("width" => 250, "height" => 250, "crop" => false)
			);
		} 
				
		if ($parameters["sizes"]) {
			$file_name = str_replace("/private", "", $file_name);
			$f = $location.$file_name;
			foreach($parameters["sizes"] as $k => $s) {
				$width = $s["width"];
				$height = $s["height"];	
				$offset = $width/4;
				shell_exec("convert $f -resize x".$height." -quality 100 ".$location.$k."_".$file_name);
				if ($s["crop"]) {
					shell_exec("convert ".$location.$k."_".$file_name." -crop ".$width."x".$height."+{$offset}+0 ".$location.$k."_".$file_name);
				}
			}
		}
	}
	
	function remote_file_upload($url, $parameters = array()) {
		global $FilesModel;
		global $DirectoryModel;
		
		$file = explode("/", $url);
		
		$file_data['tmp_name'] = tempnam("/tmp", "RIU");
		
		$fp = fopen($file_data['tmp_name'], "w");
		fwrite($fp, file_get_contents($url));
		fclose($fp);
		
		$file_data['name'] = $file[(count($file)-1)];
		$file_data['size'] = filesize($file_data['tmp_name']);
		$file_data['type'] = filetype($file_data['tmp_name']);
		$file_data['error'] = 0;
		
		$FilesModel->resource($this->resource, $this->resource_id, $this->resource_type, $this->group, $this->sizes);
		if ($directory_id) $FilesModel->directory_id = $directory_id;
		
		if($file_id = $FilesModel->add($file_data, $parameters["name"], $parameters["description"])) {
			return $file_id;
		}
		
		return false;
	}
	
	function delete($sql) {
		global $CONFIG;
		
		$sql = (is_int($sql)) ? "WHERE id = '{$sql}'" : $sql;
		$File = $this->find($sql, false);

		if ($File) {
			$dir = $CONFIG["uploads_dir"].$File["directory"]["directory"];
			$files[] = $dir.$File["filename"];
			$files[] = $dir."thumb_".$File["filename"];
			$files[] = $dir."medium_".$File["filename"];
			$files[] = $dir."large_".$File["filename"];
			
			foreach ($files as $file) {
				if (file_exists($file)) unlink($file);
			}
			parent::delete($sql);
		}
	}
	
	function get_url($id, $options = array()) {
		global $FilesModel;
		global $DirectoryModel;
		
		$File = $FilesModel->find("WHERE id = '{$id}'", false);
		//print_r($File);
		//$Directory = $DirectoryModel->find("WHERE id = '{$File["directory_id"]}'", false);
		
		if ($options["ext"]) {
			$parts = explode(".",$File["file_name"]);
			array_pop($parts);
			$File["file_name"]=implode(".", $parts).".".$ext;
		}
		
		$location = "/files/".$File["directory"]["directory"];
		$original_file = $File["file_name"];
		
		if ($options["size"]) {
			if ($options["size"]["name"]) {
				$destination_file = $options["size"]["name"]."_".$File["file_name"];
				
				if (file_exists($location.$destination_file)) {
					return $location.$destination_file;
				} else {
					$offset = $options["size"]["width"]/4;
					shell_exec("convert {$location}{$original_file} -resize x{$height} -quality 100 {$location}{$destination_file}");
					if ($options["size"]["crop"]) {
						shell_exec("convert {$location}{$destination_file} -crop {$options["size"]["width"]}x{$options["size"]["height"]}+{$offset}+0 {$location}{$destination_file}");
					}
				}
			}
		}
		//if (!file_exists($_SERVER["DOCUMENT_ROOT"]."files/".$Directory["directory"].$File["file_name"])) {
		//	return "/_templates/frontend/images/noimage.gif";
		//}
		
		return $location.$File["file_name"];
	}	
	
}

