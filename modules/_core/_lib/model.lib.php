<?PHP
// v2.0
//****************************************************
// Data Model Class Library
// Last Modified: Aug 2008
//****************************************************

class Model {
	protected $OBJ;
	private $_schema;
	var $short;
	var $table;
	var $id_name;
	var $model;
	
	var $data = array();
	
	var $has_one = array();
	var $has_many = array();
	
	var $model_has_one = array();
	var $model_has_many = array();
	
	var $has_many_resources = array();
	var $has_many_bridge = array();
	var $structure = array();
	
	
	public $count = 0;
	
	function Model($OBJ, $table, $id_name = "id") {
		$this->OBJ = $OBJ;
		$this->table = $table;
		$this->id_name = $id_name;
		$this->model = get_class($this);
		
		preg_match_all('/[A-Z][^A-Z]*/', get_class($this), $this->short);
		unset($this->short[0][0]);
		unset($this->short[0][count($this->short[0])]);
		$this->short = strtolower(implode("-", $this->short[0]));
		
		$result = DBQUERY($this->OBJ, "SHOW TABLES LIKE '".$this->table."'");
		
		if (!mysql_num_rows($result)) {
			//echo "SHOW TABLES LIKE '".$this->table."'";
			$table = new Table($this->OBJ, $this->table);
			foreach($this->schema["structure"] as $structure) {
				$table->add_field($structure[0], $structure[1], $structure[2], $structure[3]);
			}
			$table->create($this->model, $this->schema["structure"]);
			
			if ($this->schema["index"]) {
				foreach($this->schema["index"] as $index) {
					$table->add_index($index);
				}
			}
		}
		
		try {
			$count_result = $this->query("SELECT COUNT(*) as count FROM {$this->table}");
			$this->count = $count_result["count"];	
		} catch (Exception $e) {
			echo "Caught";
		}
	}


	function get_count($sql = "") {
		$count_result = DBQUERY($this->OBJ, "SELECT DISTINCT $this->id_name FROM $this->table $sql");
		return mysql_num_rows($count_result);
	}
	
	function has_one($name, $table, $bridge_id, $options = array()) {
		if (!$options["id_name"]) $options["id_name"] = "id";
			
		array_push($this->has_one, array("name" => $name, "table" => $table, "bridge_id" => $bridge_id, "options" => $options));
	}
	
	function model_has_one($name, $model, $bridge_id, $options = array()) {
		if (!$options["id_name"]) $options["id_name"] = "id";
		array_push($this->model_has_one, array("name" => $name, "model" => $model, "bridge_id" => $bridge_id, "options" => $options));
	}
	
	function has_many($name, $table, $bridge_id, $options = false) {
		array_push($this->has_many, array("name" => $name, "table" => $table, "bridge_id" => $bridge_id, "options" => $options));
	}
	
	function model_has_many($name, $model, $bridge_id, $id_name = "id") {
		array_push($this->model_has_many, array("name" => $name, "model" => $model, "bridge_id" => $bridge_id, "id_name" => $id_name));
	}
	
	function has_many_resources($name, $resource, $model, $options = array()) {
		array_push($this->has_many_resources, array("name" => $name, "resource" => $resource, "model" => $model, "options" => $options));
	}
	

	
	function find_by_id_set($id_set, $recordID = "id", $page = 0, $PerPage = 5, $object = false) {
		$data = array();
		
		$start = ($page-1)*$PerPage;
		$end = (($page-1)*$PerPage)+$PerPage-1;
		
		for ($i=$start;$i<$end;$i++) {
		    $id = $id_set[$i];
		    
		    if ($id) {
				$sqlid = "WHERE $recordID=$id";
				$result = DBQUERY($this->OBJ, "SELECT * FROM $this->table $sqlid");
				$result_dump = mysql_fetch_assoc($result);
				$d = $result_dump;
	
				foreach ($this->has_one as $v) {
					$name = $v["name"];
					$bridge_id = $v["bridge_id"];
					$id_name = $v["id_name"];
				
					$ho_result = DBQUERY($this->OBJ, "SELECT * FROM ".$v["table"]." WHERE ".$id_name." = '".$d[$bridge_id]."'");
					$d[$name] = mysql_fetch_assoc($ho_result);
				}
				foreach ($this->has_many as $v) {
					$name = $v["name"];
					$bridge_id = $v["bridge_id"];
					
					$d[$name] = array();
					
					$hm_result = DBQUERY($this->OBJ, "SELECT * FROM ".$v["table"]."_".$v["name"]."_bridge WHERE id = '".$d[$bridge_id]."'");
					$d[$name] = mysql_fetch_assoc($ho_result);
				}
				array_push($data, $d);
		    }
		}
		$this->data = $data;
		
		if ($object) return $data;
		
		if (count($data)==1) return $data[0];
		else return $data;
	}
	
	function find($sql=false, $object = false, $fields = false) {
		global $CONFIG;
		global $sitename;

		$data = array();
		if (!$sql) $sql = "WHERE ".$this->id_name." = ".$this->id_name;
		$sql = (is_int($sql)) ?  "WHERE ".$this->id_name." = $sql" : $sql;
		if (!$object) $sql.= " LIMIT 0,1";
	
		if (!$fields) {
			if ($_SESSION[$sitename]["api_call"]) {
				$fields = array();
				foreach($this->fields() as $field) {
					$fields[] = $field["name"];
				}
			
				if ($this->api["disable"]){
					foreach($this->api["disable"] as $field) {
						$key = array_search($field, $fields);
						unset($fields[$key]);
					}
				}
			} 
		}
		$select = ($fields) ? implode(", ", $fields) : "*";
		
		
		$this->result = DBQUERY($this->OBJ, "SELECT $select FROM $this->table $sql");
		while($result_dump = mysql_fetch_assoc($this->result)) {
			$d = $result_dump;
			
			if ($this->language_fields) {
				$language_text = language_text($this->model, $d["id"]);
				foreach($this->language_fields as $language_field) {
					$d[$language_field] = $language_text[$language_field];
				}
			}

			foreach ($this->has_one as $v) {
				$name = $v["name"];
				$bridge_id = $v["bridge_id"];
				$id_name = $v["options"]["id_name"];
				
				if (!$d[$bridge_id]) continue;
				
				$ho_result = DBQUERY($this->OBJ, "SELECT * FROM ".$v["table"]." WHERE ".$id_name." = '".$d[$bridge_id]."'");
				
				$ho_result_dump = mysql_fetch_assoc($ho_result);
				
				$return_only = $v["options"]["return_only"];
				
				$d[$name] = ($return_only) ? $ho_result_dump[$return_only] : $ho_result_dump; 
				unset($d[$bridge_id]);
			}
			
			foreach($this->model_has_one as $v) {
				$model = $v["model"];
				global $$model;
				$name = $v["name"];
				$bridge_id = $v["bridge_id"];
				$id_name = $v["options"]["id_name"];
				$return_only = $v["options"]["return_only"];
				
				if (!$d[$bridge_id]) continue;
				
				$has_one_return = $$model->find("WHERE ".$id_name." = '".$d[$bridge_id]."'", false);
				
				$d[$name] = ($return_only) ? $has_one_return[$return_only] : $has_one_return; 
				
				unset($d[$bridge_id]);
			}
			
			foreach ($this->has_many as $v) {
				$name = $v["name"];
				$bridge_id = $v["bridge_id"];
				
				
				if (!$result_dump["id"]) continue;
				//$d[$name] = array();
				
				
				if ($v["options"]["bridge_id"] && $v["options"]["bridge_model"]) {
					$model = $v["options"]["bridge_model"];
					global $$model;
					$results = $$model->find("WHERE id IN (SELECT {$v["options"]["bridge_id"]} FROM {$v["table"]} WHERE {$bridge_id} = '{$result_dump["id"]}')", true);
					if (count($results)>0) {
						$d[$name] = $results;
					}
				} else {
				
					$hm_result = DBQUERY($this->OBJ, "SELECT * FROM ".$v["table"]." WHERE ".$bridge_id." = '".$result_dump["id"]."'");
					while($dump = mysql_fetch_assoc($hm_result)) {
						$d[$name][] = $dump; 
					}
				}
				
			}
			
			foreach ($this->has_many_resources as $v) {
				$model = $v["model"];
				global $$model;
				$name = $v["name"];
				$bridge_id = $v["bridge_id"];
				$id_name = $v["id_name"];
				$results = $$model->find("WHERE resource='{$v["resource"]}' AND resource_id= '".$d["id"]."'", true, $v["options"]["fields"]);
				
				if (count($results)>0) {
					$d[$name] = $results;
				}
			}
		
		
			array_push($data, $d);
		}
		$this->data = $data;
		
		mysql_free_result($this->result);
		
		if ($object) return $data;
		
		if (count($data)==0) return false;
		if (count($data)==1) return $data[0];
		else return $data;
		
	}
	
	function find_by_slug($field, $value) {
		$All = $this->find("WHERE id = id", true, array("id", "$field"));
		foreach($All as $a) {
			if (FORMAT_forurl($a[$field])==$value) return $this->find("WHERE id = '{$a["id"]}'");
		}
		return false;
	}

	function count($sql=false, $object = false) {
		if (!$sql) $sql = "WHERE ".$this->id_name." = ".$this->id_name;
		$data = array();
		if (is_int($sql)) $sql = "WHERE ".$this->id_name." = $sql";
		//echo $sql;
		$this->result = DBQUERY($this->OBJ, "SELECT $this->id_name FROM $this->table $sql");
		
		return mysql_num_rows($this->result);
	}
	
	function delete($sql) {
		if (!$sql) $sql = "WHERE ".$this->id_name." = ".$this->id_name;
		if (is_int($sql)) $sql = "WHERE ".$this->id_name." = $sql";
		
		$result = DBQUERY($this->OBJ, "SELECT * FROM $this->table $sql");
		$result_dump = mysql_fetch_assoc($result);
		foreach ($this->has_one as $v) {
			$name = $v["name"];
			$bridge_id = $v["bridge_id"];
			$ho_result = DBQUERY($this->OBJ, "DELETE FROM ".$v["table"]." WHERE id = '".$d[$bridge_id]."'");
		}
		
		foreach ($this->has_many as $v) {
			$name = $v["name"];
			$bridge_id = $v["bridge_id"];
			$hm_result = DBQUERY($this->OBJ, "DELETE FROM ".$v["table"]." WHERE ".$bridge_id." = '".$result_dump["id"]."'");
		}
		
		DBQUERY($this->OBJ, "DELETE FROM $this->table $sql");
	}
	
	function save($Object) {
		foreach ($this->has_one as $v) {
			$name = $v["name"];		
			if ($Object[$name]["id"]) mySQL_update($this->OBJ, $v["table"], $Object[$name], $Object[$name]["id"]);
		}
		
		foreach ($this->has_many as $v) {
			$name = $v["name"];	
			foreach($Object[$name] as $value) {	
				if ($value["id"]) mySQL_update($this->OBJ, $v["table"], $value, $value["id"]);
			}
		}
		
		mySQL_update($this->OBJ, $this->table, $Object, $Object["id"]);
	}
	

	function get_extra_values($desc, $associations = array()) {
		$object = array();
		
		
		foreach ($this->data as $k => $d) :
			$fields_table = "se_".$desc."fields";
			$fields_result = DBQUERY($this->OBJ, "SELECT * FROM $fields_table ORDER BY ".$desc."field_id");
			
		    $value_table = "se_".$desc."values";
			
			while($fields_result_dump = mysql_fetch_assoc($fields_result)) {
				//print_r($fields_result_dump);
				$field_id = $fields_result_dump[$desc."field_id"];
				$field_name = replace_with_underscore(strtolower($fields_result_dump[$desc."field_title"]));
				
				//echo "SELECT * FROM  $value_table WHERE ".$desc."value_id";
				$value_result = DBQUERY($this->OBJ, "SELECT * FROM  $value_table WHERE ".$desc."value_id = $field_id ");
				$value_result_dump = mysql_fetch_assoc($value_result);			
				//print_r($value_result_dump);
				$this->data[$k]["details"][$field_name] = $value_result_dump[($desc."value_".$field_id)];
			}
		endforeach;
		
		return $this->data[$k]["details"];
	}
	
	function clear() {
		$this->data = array();
	}
	
	function insert($values) {
		
		//$values = $this->format($values);
		
		foreach($this->model_has_one as $v) {
			$model = $v["model"];
			global $$model;
			$name = $v["name"];
			$bridge_id = $v["bridge_id"];

			if ($values[$name]) {
				if ($values[$name]["id"]) {
					$values[$bridge_id] = $$model->update($values[$name], str2int($values[$name]["id"]));
				} else {
					$values[$bridge_id] = $$model->insert($values[$name]);
				}
			}
		}
			
		return mySQL_insert($this->OBJ, $this->table, $values);
	}
	
	function format($values = array()) {
		if ($this->format) {
			print_r($this->format);
		}
	}
	
	function single_update($field, $value, $sql) {
		DBQUERY($this->OBJ, "UPDATE {$this->table} SET {$field}='{$value}' {$sql}");
	}
	
	function update($values, $sql) {
		//$values = $this->format($values);
		
		if (!$sql) $sql = "WHERE ".$this->id_name." = ".$this->id_name;
		if (is_int($sql)) $sql = "WHERE ".$this->id_name." = $sql";
		
		$a = $this->find($sql, false);
		
		foreach($this->model_has_one as $v) {
			
			$model = $v["model"];
			global $$model;
			$name = $v["name"];
			$bridge_id = $v["bridge_id"];
			
			if ($values[$name]) {
		
				$b = $$model->find("WHERE id = '{$a[$bridge_id]}'");
				if ($b) {
					$$model->update($values[$name], str2int($a[$bridge_id]));
					//echo "<br />Updating ".$model;
				} else {
					$values[$bridge_id] = $$model->insert($values[$name]);
					//echo "<br />Insert".$model;
				}
			}
			
		}
		
		return mySQL_update($this->OBJ, $this->table, $values, $a["id"]);
	}
	
	function insert_if_doesnt_exist($values, $fields=array()) {

		$sql = array();
		foreach($fields as $f) {
			$sql[] = "{$f}='".addslashes($values[$f])."'";
		}
		$sql="WHERE ".implode(" AND ", $sql);

		$exists = $this->find($sql, true);
		if (count($exists)==0) $this->insert($values);
		return $exists[0]["id"];
	}
	

	
	function disable($id) {
		$values["status"] = "Disabled";
		return mySQL_update($this->OBJ, $this->table, $values, $id);
	}
	
	function activate($id) {
		$values["status"] = "Active";
		return mySQL_update($this->OBJ, $this->table, $values, $id);
	}	
	
	function status($status, $id) {
		$values["status"] = $status;
		return mySQL_update($this->OBJ, $this->table, $values, $id);
	}	
	
	function op() {
		if ($_POST) {
			if ($_POST["op"]) {
				if (is_array($_POST["ids"])) {
					foreach($_POST["ids"] as $id) {
						if ($_POST["op"]=="delete") $this->delete(str2int($id));
						else if ($_POST["op"]=="disable") $this->disable(str2int($id));
						else if ($_POST["op"]=="activate") $this->activate(str2int($id));
					}
				}
			}
		}
	}
	
	function ids($sql, $id_value = "id") {
		$Values = $this->find($sql, true, array($id_value));
		
		$ids = array();
		foreach($Values as $value) {
			$ids[] = $value[$id_value];
		}
		return $ids;
	}
	
	function options($field1, $field2, $extra = array()) {
		$options = array();
		$sql = ($extra["sql"]) ? $extra["sql"] : "WHERE id = id";
		
		$a = $this->find($sql, true, array($field1, $field2));
		
		if ($extra["pre"]) {
			$options[] = array("", $extra["pre"]);	
		}
		foreach($a as $result) {
			$key = $result[$field1];
			$value = $result[$field2];
			$options[] = array($key,  stripslashes($value));	
		}
		return $options;
	}
	
	function get_field($name, $id = false) {
		if ($id) $sql = "WHERE id = '".$id."'";
		$field_result = DBQUERY($this->OBJ, "SELECT $name FROM $this->table $sql");
		return mysql_fetch_assoc($field_result);
	}
	
	function display($id, $field = "name") {
		$result = $this->find("WHERE id = '{$id}'", false, array($field));
		return $result[$field];
	}
	
	function random($field = "id") {
		$result = $this->find("WHERE id = id ORDER BY RAND()", false, array($field));
		return $result[$field];
	}
	
	function fields($names_only = false) {
		$fields_result = DBQUERY($this->OBJ, "SELECT * FROM $this->table");
		$columns = mysql_num_fields($fields_result);
		$FIELDS = array();
		for($i = 0; $i < $columns; $i++) {
			
			if ($names_only) {
				array_push($FIELDS, mysql_field_name($fields_result, $i));
			} else {
				array_push($FIELDS, array(
					"name" => mysql_field_name($fields_result, $i),
					"type" => mysql_field_type($fields_result, $i)
				));
			}
		}
		return $FIELDS;
	}

	function parameters() {
		$numargs = func_num_args();
		echo "Number of arguments: $numargs<br />\n";
		if ($numargs >= 2) {
			echo "Second argument is: " . func_get_arg(1) . "<br />\n";
		}
		$arg_list = func_get_args();
		for ($i = 0; $i < $numargs; $i++) {
			echo "Argument $i is: " . $arg_list[$i] . "<br />\n";
		}
	}
	
	function upload_file($file, $parameters = array()) {
		global $FilesModel;
		//print_r($file);
		if ($file && $file["error"]==0) {
			$FilesModel->resource($this->resource, $_GET["id"], false);
			if ($parameters["value"]) {
				$FilesModel->delete(str2int($parameters["value"]));
			}
			$file_id = $FilesModel->add($file, $parameters);
			$this->single_update($parameters["key"], $file_id, "WHERE id = '{$_GET["id"]}'");
		} 
	}
	
}

class ModelPaging {
	var $Object = array();
	var $pages;
	var $current_page;
	var $Model;
	var $total;
	
	function ModelPaging($Model, $id_field_name, $PerPage = 5, $sql="", $id_set = false) {
		$this->Model = $Model;

		$total = $Model->get_count($sql);
		$this->total = $total;

	    $this->pages = ($total/$PerPage);
		//$this->pages++;
		
	    if (!is_int($this->pages)) {
	    	$this->pages = round($this->pages);
	    }

		$this->current_page = ($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
		$first_item = ($this->current_page-1)*$PerPage;
		$last_item = (($this->current_page-1)*$PerPage)+$PerPage-1;
	
		$limit = " LIMIT ".$first_item.", ".$PerPage;

		if ($id_set) $this->Object = $Model->find_by_id_set($id_set, $id_field_name, $this->current_page, $PerPage, true);
		else $this->Object = $Model->find($sql.$limit, true);

	}
	
	function get_object() {
		return $this->Object;
	}
	
	function get_count($sql) {
		return $this->Model->get_count($sql);
	}
	
	function links($page = "?", $accepted_params = array()) {
		if ($this->pages<=1) return false;
		
		$first_page = $this->current_page-5;
		$last_page = $this->current_page+6;
		
		if ($last_page>$this->pages) {
			$last_page = $this->pages;
			$first_page = $this->pages-10;
		}
		if ($first_page<0) {
			$first_page = 1;
			$last_page = 10;
			
		}
		if ($last_page>$this->pages) $last_page = $this->pages;
		
		$params="";
		foreach ($_REQUEST as $k => $v) {
			if ($v!="" && $v!="null" && in_array($k, $accepted_params)) {
			$params.= "&".$k."=".$v;
			}
		}
		
		$output ='<div style="float: left; margin-top: 15px;">';
		$output.='Page '.$this->current_page.' of '.$this->pages;
		$output.=' ('.$this->total.' Results)';
		$output.='</div>';
		
		$output.='<ul class="paging">';
		for($p=$first_page; $p<=$last_page;$p++) {
			if ($p==$this->current_page) $output.= '<li class="active">'.$p.'</li>';
			else $output.= '<li><a href="'.$page.'page='.$p.$params.'">'.$p.'</a></li>';
		}
		return $output.'</ul><div style="clear: both;"></div>';
	}
	
	function verify() { }
	
	function address_id($id) {
		$A = $this->find("WHERE id = '{$id}'", false, array("address_id"));
		return $A["address_id"];
	}
	function contact_id($id) {
		$C = $this->find("WHERE id = '{$id}'", false, array("contact_id"));
		return $C["contact_id"];
	}
}

function get_models_dir() {
	return $dir=$path."_config/models/";	
}

function load_models() {
	global $CONFIG;

	$dir=$path."_config/models/";
	
	foreach (func_get_args() as $name) {
		$file = $dir.$name.".php";
		//echo $file;
		if (file_exists($file)) {
			require_once ($file);
			//echo $file;
		} else {
			global $$name;
		}
	}
}

function replace_with_underscore($string, $characters = array(" ", "/")) {
	$output = $string;
	foreach ($characters as $c){
		$output = str_replace($c, "_", $output);
	}
	
	return $output;
}
?>