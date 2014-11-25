<?php 
class Table {
	var $tbl_name;
	var $fields = array();
	var $sql = "";
	var $CONFIG;
	var $allowed_types = array("set", "float", "text", "tinytext", "longtext", "bigint", "tinyint", "int", "decimal", "varchar", "longblob", "tinyblob");
		
	
	function Table($OBJ, $name) {
		$this->CONFIG = $OBJ;
		$this->tbl_name = $name;
		$this->sql.= "CREATE TABLE `$this->tbl_name` (";
        $this->sql.= "`id` int NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`) ";
	} 
	
	function add_field($name, $type, $value = false, $default = false) {
		global $feedback;	

		if (in_array($type, $this->allowed_types)) {
			$this->sql.= ", `$name` $type";
			if ($value) $this->sql.=" ($value)";
			if ($default) $this->sql.=" DEFAULT '$default'";
		}  else {
			array_push($feedback, "The field type '.$type.' is not allowed in '.$this->tbl_name.$name.' ");
		}
	}
	
	function insert($object) {
		return mySQL_insert($this->CONFIG, $this->tbl_name, $object);
	}
	
	function create($model = false, $structure = false) {
		$this->sql.= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		DBQUERY($this->CONFIG, $this->sql);
		mySQL_insert($this->CONFIG, "_schema", array("table_name" => $this->tbl_name,  "model_name" => $model, "structure" => serialize($structure),"date_added" => TODAYSDATE));
	}
	
	function add_index($name) {
		DBQUERY($this->CONFIG, "ALTER TABLE $this->tbl_name ADD INDEX(".$name.")");
	}
	
	function alter($method, $field, $type, $value = false, $default = false) {
		
		if (in_array($type, $this->allowed_types)) {
			$type = $type;
			if ($value) $type.=" ($value)";
			if ($default) $type.=" DEFAULT '$default'";
		}  else {
			array_push($feedback, "The field type '.$type.' is not allowed in '.$this->tbl_name.$name.' ");
		}
		
		if ($method==strtolower("add")) $sql = "ALTER TABLE $this->tbl_name ADD $field $type";
		if ($method==strtolower("drop")) $sql = "ALTER TABLE $this->tbl_name DROP COLUMN $field";
		if ($method==strtolower("alter")) $sql = "ALTER TABLE $this->tbl_name ALTER COLUMN $field $type";
		
		DBQUERY($this->CONFIG, $sql);
	}
}
