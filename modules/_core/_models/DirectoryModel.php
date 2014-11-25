<?php
$DirectoryModel = new DirectoryModel($CONFIG["site"], "tbl_main_directories");
$DirectoryModel->has_many("parent", "tbl_main_directories", "parent_id");
$DirectoryModel->has_many("files", "tbl_main_files", "directory_id");


class DirectoryModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("parent_id", "bigint"),
			array("resource", "varchar", 255),
			array("resource_id", "bigint"),
			array("resource_type", "set", "'image', 'file'"),
			array("name", "varchar", 255),
			array("directory", "varchar", 255),
			array("order", "bigint"),
			array("date_added", "bigint")
		)
	);
	

	function get_parent_directory($id) {
		global $CONFIG;
		$result = DBQUERY($CONFIG["site"], "SELECT * FROM $this->table WHERE id = '".$id."'");
		$dump = mysql_fetch_assoc($result);
		return $dump["directory"];
	}
	
	function create_from_resource($parameters = array()) {
		global $CONFIG;

		$v["resource"] = ($parameters["resource"]) ?  strtolower($parameters["resource"]) : "default";
		$v["resource_id"] = ($parameters["resource_id"]) ?  $parameters["resource_id"] : false;
		$v["resource_type"] =  ($parameters["resource_type"]) ?  strtolower($parameters["resource_type"]) : false;
		$v["name"] = ($parameters["name"]) ? strtolower($parameters["name"]) : false;
		
		$path = array();
		
		$path[] = $v["resource"];
		$sql = "";
		
		if ($v["resource_id"]) {
			$path[] = $v["resource_id"];
			$sql.= " AND resource_id = '".$v["resource_id"]."'";
		}
		if ($v["resource_type"]) {
			$path[] = $v["resource_type"];
			$sql.=" AND resource_type = '".$v["resource_type"]."'";
		}
		
		if ($v["name"]) {
			$path[] = $v["name"];
			$sql.="AND name = '".$v["name"]."'";
		}
		
		$dir = implode("/", $path)."/";
		
		$result = DBQUERY($CONFIG["site"], "SELECT id FROM $this->table WHERE resource = '{$v["resource"]}' $sql");
		
		if (mysql_num_rows($result)==1) {
			$dump = mysql_fetch_assoc($result);
			return $dump["id"];
		} else {
		
			$create_directory = $CONFIG["uploads_dir"];
			
			//print_r($path);
			foreach($path as $sub_directory) {
				$create_directory.=$sub_directory."/";
	
				if (!file_exists($_SERVER["DOCUMENT_ROOT"].$create_directory)) {
					//echo "Create directory {$create_directory} <br /><br />";
					@mkdir($create_directory, 0777, true);
				} 
			}
	
			$v["directory"] = $dir;
			$directory_id = $this->insert($v);
		
			$directory_exists = (file_exists($CONFIG["uploads_dir"].$dir)) ? true : false;
			
			if ($directory_exists) {
				return $directory_id;	
			} else {	
				die('Could not create directory '.$CONFIG["uploads_dir"].$dir);
			}
		}	
		
		return false;
	}


	function add($parameters = array()) {
		$Directory = $this->find(str2int($parameters["parent_id"]));
		
		if ($Directory){
			echo $Directory["directory"];
		}
		$result = DBQUERY($CONFIG["site"], "SELECT id FROM $this->table WHERE resource = '".$v["resource"]."' AND resource_id = '".$v["resource_id"]."' AND resource_type = '".$v["resource_type"]."'");
	}
	
	function mkdir_recursive($dir) {
		global $CONFIG;
		$directories = explode("/", $dir);
			
		foreach ($directories as $directory) {
			$new.="/".$directory;
			
			if(mkdir($CONFIG["uploads_dir"].$new, 0777, true)) {
				echo 'CREATED'.$new;
			}
			
		}
	}
	
	function rename($directory_id, $name) {
		global $CONFIG;
		$Directory = $this->find(str2int($directory_id), false);

		if ($Directory) {
			$dir = explode("/", $Directory["directory"]);
			array_pop($dir);
			array_pop($dir);
			$dir = implode("/", $dir)."/";
			rename($CONFIG["uploads_dir"].$Directory["directory"], $CONFIG["uploads_dir"].$dir.$name."/");
			
			$v["name"] = $name;
			$v["directory"] = $dir.$name."/";
			mySQL_update($this->OBJ, $this->table, $v, $directory_id);
			return true;
		}		
		return false;
	}
	
	function delete_directory($directory_id) {
		global $CONFIG;
		$Directory = $this->find(str2int($directory_id));
		
		if ($Directory) {
			if (rm_recurse($CONFIG["uploads_dir"].$Directory["directory"])) {
				$this->delete(str2int($Directory["id"]));
				return true;
			}
		}
			
		return false;
	}
	
}

