<?php
$ModuleMailingListsContactsModel = new ModuleMailingListsContactsModel($CONFIG["site"], "tbl_module_mailinglist_contacts");
$ModuleMailingListsContactsModel->has_one("contact", "tbl_contacts", "contact_id");
$ModuleMailingListsContactsModel->has_one("address", "tbl_addresses", "address_id");

class ModuleMailingListsContactsModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("mailinglist_id", "int", 11, 1),
			array("contact_id", "int", 11),
			array("verification_code", "varchar", 255),
			array("verified", "int", 1, 0),
			array("date", "bigint"),
			array("date_added", "bigint")
		),
		"index" => array(
			"mailinglist_id",
			"contact_id"
		)
	);
	
	function insert($values) {
		global $AddressesModel;
		global $ContactsModel;
		
		if ($values["contact_name"]) {
			$contact_name = explode(" ", $values["contact_name"]);
			$values["contact"]["first_name"] = $contact_name[0];
			$values["contact"]["last_name"] = $contact_name[1];
			unset($values["contact_name"]);
		}
		if ($values["zip_postal_code"]) {
			$values["address"]["zip_postal_code"] = $values["zip_postal_code"];
			unset($values["zip_postal_code"]);
		}
		
		if ($values["email"]) {
			$values["contact"]["email"] = $values["email"];
			unset($values["email"]);
		}
		
		$VALID["email"] = VALID_email($values["contact"]["email"]);
		
		$Contacts = $ContactsModel->find("WHERE email = '{$values["contact"]["email"]}' AND id IN (SELECT contact_id FROM {$this->table})", true, array("id"));

		$VALID["email_exists"] = validate(
			count($Contacts)==0, 
			"Email already exists."
		);
		
		if (isnotnull($VALID)) {
			$values["contact_id"] = $ContactsModel->insert($values["contact"]);
			$values["address_id"] = $AddressesModel->insert($values["address"]);
			return parent::insert($values);	
		}
		return false;
	}

}

