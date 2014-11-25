<?php
$ModuleUserOauthModel = new ModuleUserOauthModel($CONFIG["site"], "tbl_module_user_oauth");

class ModuleUserOauthModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("user_id", "text"),
			array("email", "varchar", 255),
			array("provider", "varchar", 60),
			array("signature", "text"),
			array("timestamp", "text"),
			array("UIDSignature", "text"),
			array("signatureTimestamp", "text"),
			array("UID", "text"),
			array("nickname", "text"),
			array("photoURL", "text"),
			array("thumbnailURL", "text"),
			array("birthday", "int"),
			array("gender", "varchar", 60),
			array("country", "varchar", 60),
			array("state", "varchar", 60),
			array("city", "varchar", 60),
			array("zip_postal_code", "varchar", 60),
			array("profile_url", "varchar", 255),
			array("loginProviderUID", "varchar", 255),
			array("date_added", "int")
		)
	);
	
	function insert($values) {
		global $UsersModel;
		
		$UsersModel->ACTIVE_SESSION();
		
		$values["user_id"] = $UsersModel->ACTIVE_SESSION["id"];
		$values["birthday"] = strtotime($values["birthDay"]."-".$values["birthMonth"]."-".$values["birthYear"]);
		$values["zip_postal_code"] = $values["zip"];
		
		if ($values["provider"]=="facebook") {
			$location = explode(",", $values["city"]);
			$values["city"] = trim($location[0]);
			$values["state"] = trim($location[1]);
		} else if ($values["provider"]=="twitter") {
			$location = explode(",", $values["country"]);
			$values["city"] = trim($location[0]);
			$values["state"] = trim($location[1]);
		} else if ($values["provider"]=="linkedin") {
			$location = explode(",", $values["state"]);
			$values["city"] = trim($location[0]);
			$values["state"] = trim($location[1]);
		}
		
		if (!$UsersModel->ACTIVE_SESSION["contact"]) {
			$UsersModel->ACTIVE_SESSION["contact"] = array();
		}
		
		if (!$UsersModel->ACTIVE_SESSION["email"] && $values["email"]) {
			$UsersModel->ACTIVE_SESSION["email"] = $values["email"];
			$UsersModel->ACTIVE_SESSION["contact"]["email"] = $values["email"];
		}
		
		if (!$UsersModel->ACTIVE_SESSION["zip_postal_code"] && $values["zip_postal_code"]) {
			$UsersModel->ACTIVE_SESSION["zip_postal_code"] = $values["zip_postal_code"];
		}
		
		if (!$UsersModel->ACTIVE_SESSION["contact"]["first_name"] &&  $values["firstName"]) {
			$UsersModel->ACTIVE_SESSION["contact"]["first_name"] = $values["firstName"];
		}
		
		if (!$UsersModel->ACTIVE_SESSION["contact"]["last_name"] &&  $values["lastName"]) {
			$UsersModel->ACTIVE_SESSION["contact"]["last_name"] = $values["lastName"];
		}	
		
		//print_r($UsersModel->ACTIVE_SESSION);
		//die();
		
		$UsersModel->UPDATE_SESSION($UsersModel->ACTIVE_SESSION);
	
		
		$Exists = $this->find("WHERE user_id = '{$values["user_id"]}' AND provider = '{$values["provider"]}'", false, array("id"));
		
		if ($Exists) {
			$this->update($values, str2int($Exists["id"]));
			$id = $Exists["id"];
		} else {
			$id = parent::insert($values);
		}
		return $id;
	}
}