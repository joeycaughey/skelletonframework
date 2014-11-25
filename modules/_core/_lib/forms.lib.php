<?php
/*
$form = new FormHelper("name", "/action/", array());
$form->legend("Contact Information");
$form->input("", array("label" => "First Name", "required" => VALID_notnull)); 
$form->phone("User/Contact/phone", array("label" => "Phone")); 
$form->address("User/address", array("label" => "Address", "required" => VALID_address)); 
$form->legend("User Information");
$form->input("User/email", array("label" => "Test", "required" => VALID_useremail)); 
$form->password("User/password", array("label" => "Test", "required" => VALID_password)); 
$form->legend("Date/Time Information");
$form->date("User/date", array("label" => "Date", "required" => VALID_date)); 
$form->time("User/time", array("label" => "Time", "required" => VALID_time)); 
$form->button("Submit");

if ($form->validates()) {
	$form->snyc_value("User/Contact/email", "User/email");
	$form->set_value("User/verification_code", "sdfagsadgsdgadg");
	$form->save();
}

$form->display_feedback();
$form->render();
*/

class FormHelper {
	var $output;
	var $Object = array();
	var $feedback;
	var $first_label = true;
	var $data = array();
	
	public function FormHelper($name = "form", $action = false, $options = array())  {		
		$method = ($options["method"]=="GET") ? "GET" : "POST"; 
		$action = ($action) ? 'action="'.$action.'"' : '';
		
		$this->output.= '<form name="'.$name.'" '.$action.' method="'.$method.'" enctype="multipart/form-data" class="standard">';
	}
	
	public function render() {
		$this->output.= $this->display_feedback();
		$this->output.= "<p style='color: #7b170d;'>* All fields marked red are required.</p>";
		$this->form_build();
		echo $this->output;
		echo '</ol>';
		echo '</fieldset>';
		echo '</form>';
	}
	
	public function sync_value($name, $value) {
		
	}
	
	public function set_value($name, $value) {
		if($_POST) {
			
		}
		
	}
	
	
	public function get_value($name) {
		
	}
	
	private function form_build() {
		foreach($this->Object as $object) {
			
			if (strtolower($object["type"])=="legend") {
				if (!$first_label) {
					$this->output.= "</ol></fieldset>";
					$first_label= false;
				}
				$this->output.="<fieldset><legend>".$object["name"]."</legend><ol>";
			} else {
				$this->output.=$this->form_object($object);
			}
			
			$this->output = str_replace("<fieldset><ol></ol></fieldset>","", $this->output);
			//print_r($object);
			//$this->output = tidy_parse_string($this->output );
		}
	}
	
	public function display_feedback() {
		return display_feedback();
	}
	
	function field_name($input) {
		$parts = explode("[", $input);
		
		$field = $parts[count($parts)-1];
		$field = str_replace("_", " ", $field);
		$field = str_replace("]", "", $field);
		return FORMAT_camelcase($field);
	}
	
	function name_to_variable($input) {
		$output = array();
		$obj = explode("[",str_replace("]"," ",$input));
		foreach($obj as $o) {
			$output[]= '["'.trim($o).'"]';
		}
		return implode("", $output);
	}
	
	function field_name_to_id($input) {
		$output = array();
		$obj = explode("[",str_replace("]"," ",$input));
		foreach($obj as $o) {
			$output[]= trim($o);
		}
		return implode("_", $output);
	}

	private function form_object($object)  {
		global $RegionsModel;
		global $CountriesModel;
		
		$Regions = $RegionsModel->find("WHERE id=id AND country_id = '2' AND status = 'Active'");
		$Countries = $CountriesModel->find("WHERE id='2' AND status = 'Active'", true);
		
		if ($_POST) $this->data = $_POST;
		//echo '<pre>';
		//print_r($this->data);
		//echo '</pre>';
		$classes = array();		
		if ($object["options"]["required"]) $classes[] = "required";
		if ($object["type"]) $classes[] = $object["type"];
		$classes = implode(" ", $classes);
		
		$variable = '$this->data'.$this->name_to_variable($object["name"]);
		
		eval('$value'." = ".$variable.";");
		
		if (!is_array($value)) $value = stripslashes($value);
		
		$output = "";
		if (strtolower($object["type"])=="checkboxes" || strtolower($object["type"])=="radio") {
				$type = (strtolower($object["type"])=="checkboxes") ? "checkbox" : "radio";
			
				$output .= '</ol>';
				$output .= '</fieldset>';
				$output .= '<fieldset class="checkboxes">';
				$output .= '<legend>'.$object["options"]["label"].'</legend>';
				if ($object["options"]["note"]) {
					$output .= '<p class="note">';
					$output .= $object["options"]["note"];
					$output .= '</p>';
				}
				
				$output .= '<ol>';
				
				
				if (is_array($object["options"]["options"][0])) {
					foreach($object["options"]["options"] as $op) {
						$output .= '<li>';
						$output .= '<label><input type="'.$type.'" name="'.$object["name"].'" value="'.$op[0].'">'.$op[1].'</label>';
						$output .= '</li>';	
					}
				} else {
					foreach($object["options"]["options"] as $op) {
						$output .= '<li>';
						$output .= '<label><input type="'.$type.'" name="'.$object["name"].'" value="'.$op.'">'.$op.'</label>';
						$output .= '</li>';	
					}
				}
				$output .= '</ol>';
				$output .= '</fieldset>';
				$output .= '<div style="clear: both;"></div>';
				$output .= '<fieldset>';
				$output .= '<ol>';
		} else if (strtolower($object["type"])=="checkbox") {
				$output .= '<li class="checkbox">';
				$output .= '<label><input type="checkbox" name="'.$object["name"].'" value="Yes">'.$object["options"]["label"].'</label>';
				$output .= '</li>';	
		} else if (strtolower($object["type"])=="textarea") {
			$output .= '</ol>';
			$output .= '</fieldset>';
			$output .= '<fieldset>';
			$output .= '<legend>'.$object["options"]["label"].'</legend>';
			$output .='<textarea name="'.$object["name"].'" id="'.$this->field_name_to_id($object["name"]).'">'.parse_content($value).'</textarea>';
			$output .= '</fieldset>';
			$output .= '<fieldset>';
			$output .= '<ol>';
		} else if (strtolower($object["type"])=="password") {
			$output .= '<li class="'.$classes.'">';
			$output .= '<label for="'.$object["name"].'">'.$object["options"]["label"].'</label>';
			$output .= '<input type="password" name="'.$object["name"].'" value="" />';
			$output .= '</li>';
			$output .= '<li class="'.$classes.'">';
			$output .= '<label for="'.$object["name"].'">Confirm '.$object["options"]["label"].'</label>';
			$output .= '<input type="password" name="confirm_password" value="" />';
			$output .= '</li>';
		} else if (strtolower($object["type"])=="html") {
			$output .= $object["html"];	
		} else if (strtolower($object["type"])=="buttons") {
				//print_r($object);	
				$output .= '<li class="buttons">';
				foreach($object["buttons"] as $button) {
					$action["id"] = $button[0];
					$action["options"] = ($button[1]) ? $button[1] : array();
					$action["type"] = ($action["options"]["type"]) ? $action["options"]["type"] : "submit";
					$action["class"] = ($action["options"]["class"]) ? $action["options"]["class"] : "";
					$action["value"] = ($action["options"]["value"]) ? $action["options"]["value"] : 1;

					if ($action["options"]["url"]) {
						$action["type"] = "button";
						$this->js.= '$("#'.$button[0].'").click(function(){';
						$this->js.= '		document.location = \''.$action["options"]["url"].'\';';
						$this->js.= '});';						
					}
					
				
					if ($action["type"]=="option") {
						$output .= '<label><input type="radio" name="'.$action["id"].'" id="'.$action["id"].'" value="'.$action["value"].'" />'.$action["options"]["label"].'</label>';
					} else {
						$output .= '<button type="'.$action["type"].'" id="'.$action["id"].'" class="'.$action["class"].'">'.$action["options"]["label"].'</button>';
					}
					
				}
				$output .= "</li>";
			} else if (strtolower($object["type"])=="billing") {	
				ob_start();
				?>
				<li class="billing">
					<table class="billing">
						<tr>
							<td>
								<label>Card Number</label>
							</td>
							<td>
								<label>Month</label>
							</td>
							<td>
								<label>Year</label>
							</td>
							<td>
								<label>CVV</label>
							</td>
						</tr>
						<tr>
							<td>
								<input name="<?=$object["name"]?>[number]" value=""  size="25" />
							</td>
							<td>
								<input name="<?=$object["name"]?>[exp][month]" size="3" value="" />
							</td>
							<td>
								<input name="<?=$object["name"]?>[exp][year]" size="3" value="" />
							</td>
							<td>
								<input name="<?=$object["name"]?>[cvv]" size="4" value="" />
							</td>
						</tr>
					
					</table>
				</li>
				<?php 
				$output.= ob_get_clean();
		} else if (strtolower($object["type"])=="button") {	
			ob_start();
			?>
				<li class="button">
				 	<button type="submit"><?=$object["name"]?></button>
				</li>
			<?php 
			$output.= ob_get_clean();
	
		} else if (strtolower($object["type"])=="hidden") {
			$output .= '<input type="hidden" name="'.$object["name"].'" value="'.$value.'" />';
		} else {
			
			$output = '<li class="'.$classes.'">';
			$output.= '<label for="'.$object["name"].'">'.$object["options"]["label"].'</label>';
			if (strtolower($object["type"])=="input") {
				$output.='<input type="text" name="'.$object["name"].'" value="'.parse_content($value).'" id="'.$this->field_name_to_id($object["name"]).'" size="35" />';
			} else if (strtolower($object["type"])=="days") {
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" size="4" maxlength="5" /> Days';
			} else if (strtolower($object["type"])=="months") {
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" size="4" maxlength="3" /> Months';
			} else if (strtolower($object["type"])=="date") {
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" size="15" maxlength="15" /> (ex. Mar 01, 2010)';
			} else if (strtolower($object["type"])=="currency") {
				$value = ($value) ? $value : "0.00";
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" class="currency" size="10" maxlength="10" />';
			} else if (strtolower($object["type"])=="percent") {
				$value = ($value) ? $value : "0";
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" class="percent" size="4" maxlength="3" /> %';
			} else if (strtolower($object["type"])=="phone") {	
				$output.='<input type="text" name="'.$object["name"].'" value="'.$value.'" id="'.$this->field_name_to_id($object["name"]).'" size="18" />';
			} else if (strtolower($object["type"])=="note") {
				$output.="<p>{$object["name"]}</p>";
			} else if (strtolower($object["type"])=="file") {
				$output.='<input name="'.$object["name"].'" type="file"  />';
			} else if (strtolower($object["type"])=="address") {
				$output.= '<input type="text" name="'.$object["name"].'[line1]" value="'.$value["line1"].'"  />';
				$output.= '</li>';
				$output.= '<li>';
				$output.= '<label for="'.$object["name"].'">Address 2</label>';
				$output.= '<input type="text" name="'.$object["name"].'[line2]" value="'.$value["line2"].'"  />';
				$output.= '</li>';
				$output.= '<li class="'.$classes.'">';
				$output.= '<label for="'.$object["name"].'">City</label>';
				$output.= '<input type="text" name="'.$object["name"].'[city]" value="'.$value["city"].'"  />';
				$output.= '</li>';
				$output.= '<li class="'.$classes.'">';
				$output.= '<label for="'.$object["name"].'[region]">Province/State</label>';
				$output.= '<select name="'.$object["name"].'[region]">';
				foreach($Regions as $ps) {
					$selected = ($value["region"]==$ps["abbrev"]) ? 'selected' : ''; 
			        $output.= '<option value="'.$ps["abbrev"].'" '.$selected.'>'.$ps["name"].'</option>';
			     }
				$output.= '</select>';
				$output.= '</li>';
				$output.= '<li class="'.$classes.'">';
				$output.= '<label for="'.$object["name"].'[country]">Country</label>';
				$output.= '<select name="'.$object["name"].'[country]">';
				foreach($Countries as $ps) {
					$selected = ($value["country"]==$ps["abbrev"]) ? 'selected' : ''; 
			        $output.= '<option value="'.$ps["abbrev"].'" '.$selected.'>'.$ps["name"].'</option>';
			     }
				$output.= '</select>';
				$output.= '</li>';
				$output.= '<li class="'.$classes.'">';
				$output.= '<label for="'.$object["name"].'">Zip Postal Code</label>';
				$output.= '<input type="text" name="'.$object["name"].'[zip_postal_code]" value="'.$value["zip_postal_code"].'"  />';
				$output.= '</li>';
			} else if (strtolower($object["type"])=="select") {	
				$output .= '<select name="'.$object["name"].'">';
				//print_r($object["options"]["options"]);
				
				if (is_array($object["options"]["options"][0])) {
					
					foreach($object["options"]["options"] as $op) {
						$selected = ($value==$op[0]) ? 'selected' : '';
						$output .= '<option value="'.$op[0].'" '.$selected.'>'.$op[1].'</option>';	
					}
				} else {
					
					foreach($object["options"]["options"] as $op) {
						$selected = ($value==$op) ? 'selected' : '';
						$output .= '<option '.$selected.'>'.$op.'</option>';		
					}
				}
				$output .= '</select>';

			} 
			$output.= '</li>';
		}
		return $output;
	}

	function validates()  {
		//return true;
		if (!$_POST) return false;
		
		$data = ($_POST) ? $_POST : $_GET;
		
		foreach($this->Object as $object) {
			if ($object["options"]["required"]) {
				$function = $object["options"]["required"];
				
				$variable = '$this->data'.$this->name_to_variable($object["name"]);
		
				eval('$value'." = ".$variable.";");
				
				if ($function=="VALID_notnull") {
					
					if($error = !$function($this->field_name($object["name"]), $value))	{
						$this->feedback["errors"][] = $error;
					}
				} else {
					if($error = !$function($value))	{
						$this->feedback["errors"][] = $error;
					}
				}
			}
		}
		
		if (count($this->feedback["errors"])==0) return true;
		return false;
	}
	
	function validate($function) {	
	
	}
	
	function save($id = false) {
		

		$update = array();
		if ($id) $update["id"] = $id;
		
		foreach($this->Object as $object) {
			
			if ($object["type"]!="legend") {
				$models = explode("/", $object["name"]);
				$field = array_pop($models);
				
				$value_key = $object["name"];
				$value = $_POST[$value_key];
				
				$model1 = $models[0];
				$model2 = $models[1];
				$model3 = $models[2];
				
				if (count($models)==1) {
					$update[$model1]["values"][$field] = $value;
				} else if (count($models)==2) {
					$update[$model1]["has_models"][$model2]["values"][$field] =  $value;
				} else if (count($models)==3) {
					$update[$model1]["has_models"][$model2]["has_models"][$model3]["values"][$field] = $value;	
				}
			}
			
		}
			
		foreach($update as $model => $object) {
			return $this->do_update($model, $object);
		}

	}
	
	function do_update($model, $object) {
		global $$model;
			
		echo $model."<br />";
		echo '<pre>';
		print_r($object);
		echo '</pre>';
		
		if ($object["id"]) {
			$AM = $$model->find("WHERE id = '{$object["id"]}' LIMIT 0,1", false);
		}
		
		if ($object["has_models"]) {
			foreach($object["has_models"] as $key => $values) {
				$field_id_name = $values["update_field_name"];
				$result = $this->do_update($key, $values);
			
				if (is_int($result)) {
					$object["values"][$field_id_name] = $result;
					unset($object["has_models"][$key]);	
				} 
			}
		} else {
			if ($AM) {
				echo "Update";
				$$model->update($object["values"], $AM["id"]);
				return $AM["id"];
			}
			echo "Insert";
			return $$model->insert($object["values"]);
		}
		
		if (count($object["has_models"])==0) {
			echo $model;
			return ($object["id"]) ? $$model->update($object["values"], $object["id"]) : $$model->insert($object["values"]);
		} else {
			//$this->do_update($model, $object);
		}	
	}
	
	private function create_name($input) {
		$output = "";
		
		$add = false;
		$levels = explode("/", $input);
		foreach($levels as $level) {
			$output.= ($add) ? '['.$level.']' : $level;
			$add = true;	
		}
		
		return $output;
	}
	
	private function create_id($input) {
		$output = "";
		
		$add = false;
		$levels = explode("/", $input);
		$output = strtolower(implode("_", $levels));
		
		return $output;
	}

	
	public function input($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "input");
	}

	public function password($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "password");
	}
	
	public function textarea($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "textarea");
	}
	
	public function date($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "date");
	}
	
	public function time($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "time");
	}
	
	public function select($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "select");
	}
	
	public function checkbox($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "checkbox");
	}
	
	public function checkboxes($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "checkboxes");
	}
	
	public function radio($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "radio");
	}
		
	public function file($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "file");
	}
	
	public function phone($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "phone");
	}
	
	public function address($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "address");
	}
	
	public function contact($name, $options) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "contact");
	}
	
	public function legend($name) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "legend");
	}
	
	public function button($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "button");
	}

	public function currency($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "currency");
	}
	
	public function days($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "days");
	}
	
	public function months($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "months");
	}
	
	public function percent($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "percent");
	}
	
	public function hidden($name, $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "hidden");
	}
	
	public function buttons($buttons = array()) {
		$this->Object[] = array("buttons" => $buttons, "type" => "buttons");
	}
	
	public function html($html = array(), $options = false) {
		$this->Object[] = array("html" => $html, "options" => $options, "type" => "html");
	}
	
	public function billing($name = array(), $options = false) {
		$this->Object[] = array("name" => $name, "options" => $options, "type" => "billing");
	}
}
