<?php
$ContactsModel = new ContactsModel($CONFIG["site"], "tbl_contacts");


class ContactsModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("first_name", "varchar", "30"),
			array("last_name", "varchar", "40"),
			array("company", "varchar", "100"),
			array("company_id", "int"),
			array("client_id", "int"),
			array("position", "varchar", "40"),
			array("phone", "varchar", "15"),
			array("work_phone", "varchar", "15"),
			array("mobile", "varchar", "15"),
			array("mobile_carrier", "varchar", "40"),
			array("mobile_provider_id", "tinyint", "4"),
			array("tollfree", "varchar", "15"),
			array("fax", "varchar", "15"),
			array("email", "varchar", "128"),
			array("ichat", "varchar", "128"),
			array("send_email_alerts", "set", "'Yes','No'", "No"),
			array("send_sms_alerts", "set", "'Yes','No'", "No"),
			array("best_time_to_call", "varchar", "50")
		)
	);
	

	
	function quick_display($id) {
		$C = $this->find(str2int($id));
		
		$output = "";
		
		if ($C["phone"]) $output.= "<b>phone:</b> ".$C["phone"]."<br />";
		if ($C["mobile"]) $output.= "<b>mobile:</b> ".$C["mobile"]."<br />";
		if ($C["tollfree"]) $output.= "<b>tollfree:</b> ".$C["tollfree"]."<br />";
		if ($C["email"]) $output.= "<b>email:</b> ".$C["email"]."<br />";
		if ($C["ichat"]) $output.= "<b>ichat:</b> ".$C["ichat"]."<br />";

		return $output;	
	
	}
	
	function display($id) {
		$C = $this->find(str2int($id), false, array("first_name", "last_name"));
		$output = "";
		
		if ($C["first_name"]) $output.= $C["first_name"];
		if ($C["last_name"]) $output.= " ".$C["last_name"];
		return $output;		
	}
	
	function phone($id) {
		$C = $this->find(str2int($id), false, array("phone"));
		$output = "";
		
		if ($C["phone"]) $output.= $C["phone"];
		return $output;		
	}
}

