<?PHP
// v2.0
//****************************************************
// mySQL Function Library
// Last Modified: Aug 2008
//****************************************************

function DBQUERY($OBJ, $query) {
    //*********************************************************
    //Connect to Database
    //*********************************************************
        $link = mysql_connect($OBJ["mySQL_hostname"], $OBJ["mySQL_username"], $OBJ["mySQL_password"])
      or die ("Could not connect");
    //print ("Connected successfully");
    mysql_select_db ($OBJ["mySQL_database"])
      or die ("Could not select database : ".$OBJ["mySQL_database"]);
    //*********************************************************
    //SQL Query
    //*********************************************************
    $result = mysql_query ($query)
      or die ("Query failed : $query");
        return $result;
}

function mySQL_select($OBJ, $table, $conditions='WHERE id = id') {
	$result=DBQUERY($OBJ,"SELECT * FROM $table $conditions");
	if (mysql_num_rows($result)==0) { 
		return array(); 
	} else { 
		$output = array();
		while($r = mysql_fetch_assoc($result)) {
			array_push($output, $r);	
		}
		return $output;
	}
}

function mySQL_select_precise($OBJ,$champ, $table,$conditions='WHERE id = id') {
	$result=DBQUERY($OBJ,"SELECT $champ FROM $table $conditions");
	if (mysql_num_rows($result)==0) { 
		
		return array(); 
	} else { 
		$output = array();
		while($r = mysql_fetch_assoc($result)) {
			array_push($output, $r);	
		}
		return $output;
	}
}

function mySQL_insert($OBJ, $table, $AR) {
	global $CONFIG;
	$FIELDS = array();
	
	$KEYS = "";
	$VALUES = "";
	
	$AR["spawn_id"] = $CONFIG["spawn_id"];
	$AR["date_added"] = TODAYSDATE;
	$AR["date_last_modified"] = TODAYSDATE;
	$AR["date_updated"] = TODAYSDATE;
	    
      // Get Field Names
	$fields_result = DBQUERY($OBJ, "SELECT * FROM $table");
	$columns = mysql_num_fields($fields_result);
	for($i = 0; $i < $columns; $i++) {
    	array_push($FIELDS, mysql_field_name($fields_result, $i));
    }
	//print_r($FIELDS);
	
    if ($_FIELDS["order_id"]) {
    	if ($order_number = mySQL_order_number($OBJ, $table)) {
    		$AR["order_id"] = $order_number;
    	} else {
    		$AR["order_id"] = 0;
    	}
    }
    
    if (!is_array($AR)) return false;

	foreach ($AR as $k => $v) {
		if (in_array($k, $FIELDS)) {
			$KEYS.= $k.", ";
			$VALUES.= "'".addslashes(trim($v))."', ";
		}
	}
	
	$KEYS = substr($KEYS,0, strlen($KEYS)-2);
	$VALUES = substr($VALUES,0, strlen($VALUES)-2);
	
	$SQL = "INSERT INTO $table($KEYS) VALUES($VALUES)";
	//echo $SQL;
	DBQUERY($OBJ, $SQL);
	
	return mysql_insert_id();
}

function mySQL_order_number($OBJ, $table) {
	$result = DBQUERY($OBJ, "SELECT MAX(order_id) FROM $table");
	$dump = mysql_fetch_assoc($result);
	return (mysql_num_rows($result)==1) ? $dump["order_id"] : false;
}

function mySQL_update($OBJ, $table, $AR, $recordID, $id_name = "id") {
	global $CONFIG;
	$FIELDS = array();
	 if (!is_array($AR)) return false;
	 
	unset($AR["id"]);
	$AR["date_last_modified"] = TODAYSDATE;
	$AR["date_updated"] = TODAYSDATE;
	$AR["spawn_id"] = $CONFIG["spawn_id"];
		
	$QUERY = "";
	
      // Get Field Names
	$fields_result = DBQUERY($OBJ, "SELECT * FROM $table");
	$columns = mysql_num_fields($fields_result);
	for($i = 0; $i < $columns; $i++) {
    	array_push($FIELDS, mysql_field_name($fields_result, $i));
    }
	//print_r($FIELDS);
	
	foreach ($AR as $k => $v) {
		if (in_array($k, $FIELDS)) {
			$QUERY.= $k."='".addslashes(trim($v))."', ";
		}
	}
	
	$QUERY = substr($QUERY,0, strlen($QUERY)-2);
	
	$SQL = "UPDATE $table SET $QUERY WHERE $id_name = '$recordID'";
	DBQUERY($OBJ, $SQL);
	
	$result = DBQUERY($OBJ, "SELECT $id_name FROM $table WHERE $id_name = '$recordID'");
	
	if (mysql_num_rows($result)==0) return false;
	return ($recordID);
}

function update_or_create($OBJ, $table, $AR, $recordID, $id_name = "id") {
	$AR[$id_name] = $recordID;
	$result = mySQL_update($OBJ, $table, $AR, $recordID, $id_name);
	
	if (!$result) {
		return mySQL_insert($OBJ, $table, $AR);
	}
	return $result;
}

function mySQL_table_exists($OBJ, $table) {
	$table_result = DBQUERY($OBJ, "SHOW TABLES like '$table';");
	if (mysql_num_rows($table_result)==1) {
		return true;
	} else {
		return false;
	} 
}

function mySQL_backup($OBJ, $filename) {
	//echo 'Backup Started.';
	if (true) {
		//$fh = @fopen($filename,'w');
		$mysqlDumpCmd = "mysqldump5  --host=".$OBJ["mySQL_hostname"]." --user=".$OBJ["mySQL_username"]." --password=".$OBJ["mySQL_password"]." --port=21 ".$OBJ["mySQL_database"]." | gzip -c -9 - > {$filename}";		
		//echo $mysqlDumpCmd;
		exec($mysqlDumpCmd, $output, $return);
		if($return) {
			feedback("notices", "Backup file created.");
			return true;
		}
		return true;
	}
	feedback("error", "There was an error creating the backup file.");
	return false;
}

function mySQL_restore($OBJ, $file_id) {
	global $FilesModel;
	global $DirectoryModel;
	$File = $FilesModel->find(str2int($file_id));
	$Directory = $DiretoryModel->find(str2int($File["directory_id"]));
	
	$dir=$CONFIG["uploads_dir"]."files/".$Directory["directory"];
	
	$filename = $File["filename"];
	$sql_filename = substr_replace(".gz", "", $File["filename"]);
	
	$mysqlDumpCmd = " gzip -c -9 - > ".$dir.$filename." | mysql5  --host=".$OBJ["mySQL_hostname"]." --user=".$OBJ["mySQL_username"]." --password=".$OBJ["mySQL_password"]." --port=21 ".$OBJ["mySQL_database"]." < ".dir.$sql_filename;
	
}

function mySQL_database_empty($OBJ) {

	$conn = mysql_connect($OBJ["mySQL_hostname"], $OBJ["mySQL_username"], $OBJ["mySQL_password"]) or die ('Error connecting to mysql: ' . mysql_error());
	mysql_select_db($dbname);

	// CREATE A NEW ARRAY TO STORE THE ALL TABLE NAMES
	$all_tables = array();

	// USE MYSQL'S SHOW TABLES TO GET ALL THE TABLE NAMES
	$sql = mysql_query("SHOW TABLES") or die(mysql_error());

	while($row = mysql_fetch_array($sql)) {
		$all_tables[] = $row[0];
	}

	// CREATE A NEW ARRAY THAT CONTAINS NAMES OF TABLES THAT NEED NOT BE EMPTIED
	//$not_to_empty = array('admin', 'countrylist', 'currency', 'templates', 'ip2nation', 'ip2nationcountries', 'settings');
	$not_to_empty = array();
	// FIND THE DIFFERENCE IN ARRAYS
	$truncate_tables = array_diff($all_tables, $not_to_empty);
	sort($truncate_tables);

	// RUN A LOOP TO TRUNCATE THE TABLES
	for($i=0; $i<count($truncate_tables); $i++)	{
		$truncate = mysql_query("TRUNCATE TABLE $truncate_tables[$i]") or die(mysql_error());
	}

}

class mySQL_multiple_insert {
	var $FIELDS = array();
	var $KEYS = "";
	var $sql = "";
	var $sql_inserts = array();
	
	var $table;
	var $OBJ;
	var $SQL_statements = array();
	
	function sql($sql) {
		$this->SQL_statements[] = $sql;
	}
	
	function run_sql() {
		//DBQUERY($this->OBJ, "LOCK TABLES WRITE;");  
		
		foreach($this->SQL_statements as $sql) {
		
			DBQUERY($this->OBJ, $sql);
		}
		
	}

	
	function mySQL_multiple_insert($OBJ, $table) {
		$this->OBJ = $OBJ;
		$this->table = $table;
		
		if ($table) {
			// Get Field Names
			$fields_result = DBQUERY($this->OBJ, "SELECT * FROM $this->table LIMIT 1");
			$columns = mysql_num_fields($fields_result);
			for($i = 0; $i < $columns; $i++) {
		    	array_push($this->FIELDS, mysql_field_name($fields_result, $i));
		    }
		    
		    DBQUERY($this->OBJ, "LOCK TABLES $this->table WRITE; ");  
		}
	}
	
	
	function add($AR) {
		$KEYS = "";
		$VALUES = "";
	
		if (!is_array($AR)) return false;
		
		$AR["date_added"] = TODAYSDATE;
		$AR["last_modified"] = TODAYSDATE;
		$AR["last_updated"] = TODAYSDATE;
			
		foreach ($AR as $k => $v) {
			if (in_array($k, $this->FIELDS)) {
				$KEYS.= $k.", ";
				$VALUES.= "'".addslashes(ltrim(rtrim($v)))."', ";
			}
		}
		
		$this->KEYS = substr($KEYS,0, strlen($KEYS)-2);
		$VALUES = substr($VALUES,0, strlen($VALUES)-2);
		
		$this->sql_inserts[] = "($VALUES)";	
		
	}
	
	
	function run() {
		if (count($this->sql_inserts)>0) {
			$this->sql.= "INSERT INTO $this->table($this->KEYS) VALUES ".implode(", ", $this->sql_inserts)."; ";
			//$this->sql.= "UNLOCK TABLES; ";
			//echo $this->sql;
			DBQUERY($this->OBJ, $this->sql);
			DBQUERY($this->OBJ, "UNLOCK TABLES;");
		}
	
	}
}
