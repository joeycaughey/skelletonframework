<?PHP
// v2.0
//****************************************************
// Validation Function Library
// Last Modified: Dec 2008
//****************************************************

$feedback = array();

function VALID_date($input) {
	if (preg_match("/[0-9]+\/[0-9]+\/[0-9]+/", $input)) {
		return true;
	}
	feedback("errors", "Invalid date format.");
}

function VALID_price($input) {
	if (!$input || $input<=200) {
		feedback("errors", "Your rental price must be greater than $200.");
		return false;
	} else if ($input>=5000) {
		feedback("errors", "Your rental price can not be greater than $5000.");
		return false;
	}
	return true;
}

function VALID_licensenumber($input) {
	global $FacilitiesModel;
	global $FacilityUsersModel;
	
	$F = $FacilitiesModel->find("WHERE license_number = '{$input}'", false);
	
	
	if ($FacilityUsersModel->find("WHERE facility_id = '{$F["id"]}'", true, array("id"))) {
		feedback("errors", 'This facility has already been claimed.  Iy you believe that this is an error please contact <a href="mailto:support@onn.com">support@onn.com</a>.');	
		return false;
	}
	
	if ($F) return true;
	feedback("errors", "You must enter a valid facility license number.");	
	return false;
}



function VALID_notnull($field, $input) {
	if ($input!="") return true;
	feedback("errors", "{$field} can not be blank.");	
	return false;	
}

function VALID_terms($input) {
	if ($input=="Yes") return true;
	feedback("errors", "You must select the terms and conditions.");	
	return false;	
}

function VALID_time($input) {
	if ($input!="") return true;
	return false;	
}


function VALID_useremail($input) {
	global $UsersModel;
	
	$User = $UsersModel->find("WHERE email = '$input'", true);
	if (!VALID_email($input)) {
		return false;
		
	}
	if (count($User)>0) {
		feedback("errors", "The email address {$input} already exists in our system.");	
		return false;
	} 
	return true;
}


function feedback($type, $text) {
	global $feedback;
	if (!is_array($feedback[$type])) $feedback[$type] = array();
	$feedback[$type][] = $text;
}

function display_feedback() {
	global $feedback;
	
	if ($feedback["notices"]) {
		foreach($feedback["notices"] as $message) {
			$output.='<p class="success" style="margin-top: 3px;">'.$message.'</p>';
		}	
	} else {
		$output.='<p class="success" style="display: none; margin-top: 3px;">'.$message.'</p>';
	}
	if ($feedback["errors"]) {
		
		if (count($feedback["errors"])>8) {
			$output.='<p class="error" style="color: #cc0000; margin-top: 3px;">You have errors in your form.</p>';
		} else {
			foreach($feedback["errors"] as $message) {
				$output.='<p class="error" style="color: #cc0000; margin-top: 3px;">'.$message.'</p>';
			}
		}
	} else {
		$output.='<p class="error" style="color: #cc0000; margin-top: 3px; display: none;">'.$message.'</p>';
	}
	return $output;
}

function error_feedback() {
	global $feedback;
	
	if (count($feedback)>0)
		return implode("<br />", $feedback);
	else
		return false;
}

function VALID_name($name) {
  $result = count_chars($name, 0);
  $valid=true;
  for ($i=0; $i < count($result); $i++) {
    if ($result[$i]!=0 AND (($i>=0 AND $i<=44) OR ($i>=46 AND $i<=47) OR ($i>=58 AND $i<=64) OR ($i>=91 AND $i<=94) OR ($i==96) OR ($i>=123 AND $i<199))) {
      $valid = false;
    }   
	//echo "There were $result[$i] instance(s) of \"" , chr($i) , "\" in the string.\n<br>";
  } 
  if (!$valid) { return false; } 
  else { return true; }
}

function VALID_username($username) {
  $result = count_chars($username, 0);
  $valid=true;
  for ($i=0; $i < count($result); $i++) {
    if ($result[$i]!=0 AND (($i>=0 AND $i<=44) OR ($i>=46 AND $i<=47) OR ($i>=58 AND $i<=64) OR ($i>=91 AND $i<=94) OR ($i==96) OR ($i>=123 AND $i<199))) {
      $valid = false;
    }   
	//echo "There were $result[$i] instance(s) of \"" , chr($i) , "\" in the string.\n<br>";
  } 
  if (!$valid) { return false; } 
  else { return true; }
}

function VALID_password($password, $confirm = false) {
	if (!$confirm) $confirm = $_POST["confirm_password"];
	
	global $feedback;
	$result = count_chars($password, 0);
	for ($i=0; $i < count($result); $i++) {
    	if ($result[$i]!=0 AND (($i>=0 AND $i<=44) OR ($i>=46 AND $i<=47) OR ($i>=58 AND $i<=64) OR ($i>=91 AND $i<=94) OR ($i==96) OR ($i>=123 AND $i<199))) {
      		feedback("errors", "Your password can only contain alpha numeric characters.");
      		return false;
    	}   
    	
 	} 
	if ($password==$confirm && $password!="" && strlen($password)>=5 && strlen($password)<=20) {
		return true;
	} else if ($password!=$confirm) {
		feedback("errors", "Your password must match the confirm.");		
	} else if ($password=="") {
		feedback("errors", "Your password can not be blank.");
	} else if (strlen($password)<=5 && strlen($password)>=20) {
		feedback("errors", "Your password must be 5-20 characters in length.");
	}
	return false;	
}

function VALID_groupname($groupname) {
  $result = count_chars($groupname, 0);
  $valid=true;
  for ($i=0; $i < count($result); $i++) {
    if ($result[$i]!=0 AND (($i>=0 AND $i<=31) OR ($i>=33AND $i<=44) OR ($i>=46 AND $i<=47) OR ($i>=58 AND $i<=64) OR ($i>=91 AND $i<=94) OR ($i==96) OR ($i>=123 AND $i<199))) {
      $valid = false;
	  echo $i;
    }  
	
	//echo "(".ord(chr($i)).") There were $result[$i] instance(s) of \"" , chr($i) , "\" in the string. \n<br>";
  } 
  if (!$valid) { return false; } 
  else { return true; }
}

function VALID_email($email) {
  global $feedback;
  if (strlen($email)>9 AND strlen($email)<129 AND substr_count($email,"@")==1 AND substr_count($email,".")>0) { 
  	return true; 
  } else { 
  	if (strlen($email)<9 && $email!="") {
  		array_push($feedback, "Your email is too short.");
  	} else if (strlen($email)>129 && $email!="") {
  		feedback("errors", "Your email is too long.");	
  	} else {
  		feedback("errors", "Your email is invalid.");	
  
  	}
  	return false; 
  }
}

function VALID_confirmemail($email, $confirm = false) {
	if (!$confirm) $confirm = $_POST["confirm_email"];
	if ($input==$confirm) return true;
	feedback("errors", "Your email addresses must match.");	
	return false;	
}


function VALID_phone($number) {
return true;
  if (is_array($number)) {
    if (strlen($number[0])==3 AND is_numeric($number[0]) AND strlen($number[1])==3 AND is_numeric($number[1]) AND strlen($number[2])==4 AND is_numeric($number[2])) return true;
	else return false;
  } else if (!is_array($number)) {
    if ( preg_match("/^[0-9]{3,3}[-]{1,1}[0-9]{3,3}[-]{1,1}[0-9]{4,4}$/", $number) ) return true;
	else return false;
  }
}

function VALID_address($address = array()) {
	if ($address["line1"]!="" && $address["city"]!="" && $address["proivnce_state_id"]!="null" && $address["zip_postal_code"]!="") {
		return true;
	}  
	feedback("errors", "You must enter an address.");
	return false;	

}

function VALID_zip_postal_code($string) {
	global $feedback;
    $input=$string;
    //if(strlen($input)==6) $input=substr($input,0,3)." ".substr($input,4,6);
	if ( preg_match("/[A-z]\d[A-z]\s\d[A-z]\d/", $input) || preg_match("/[A-z]\d[A-z]\d[A-z]\d/", $input)  || preg_match("/\d\d\d\d\d/", $input) ) {
		return true;
	} else {
		feedback("errors", "Enter a valid zip or postal code.");
		return false;
	}
}

function VALID_url($url) {
	global $feedback;
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	
	if (preg_match($pattern, $url)) return true;
	
	feedback("errors", "Enter a valid url.");
	return false;
}

function VALID_address_postal_only($address) {
	global $feedback;
	if (!$address["location_id"] || !$address["city_id"] || !$address["province_id"]) {
		feedback("errors", "You must enter a valid postal code.");
		return false;
	}
	return true;
}

function validate( $condition, $fb = false ) {
	if ($condition) {
		return true;
	} else {
		if ($fb) feedback("errors", $fb);
		return false;
	}
}

function isnotnull($value) {
  if (is_array($value)) {
    foreach ($value as $svalue) {
      if ($svalue==false) return false;
	}
	return true;
  } else {
    if ($value!=false) return true;
    else return false;
  }
}
