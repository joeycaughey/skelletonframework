<?php
$MessagesModel = new MessagesModel($CONFIG["site"], "tbl_module_messaging");
$MessagesModel->has_one("sent_to_user", "tbl_module_users", "sent_to_user_id");
$MessagesModel->has_one("sent_by_user", "tbl_module_users", "sent_by_user_id");


class MessagesModel extends Model {
	
	var $schema = array(
		"structure" => array(
			array("parent_id", "bigint", 16, 0),
			array("sent_to_user_id", "int"),
			array("sent_by_user_id", "int"),
			array("subject", "varchar", "255"),
			array("body", "longtext"),
			array("read", "'Yes', 'No'", "No"),
			array("type", "set", "'Sent', 'Draft'", "Draft"),
			array("archived", "set", "'Yes', 'No'", "No"),
			array("date_added", "int")
		)
	);
	
	
	function count() {
		global $UsersModel;
		$UsersModel->ACTIVE_USER();
		$Messages = $this->find("WHERE sent_to_user_id = '{$UsersModel->ACTIVE_USER["id"]}'", true, array("id"));
		return count($Messages);
	}
	
	function thread($message_id) {
		$Messages = $this->find("WHERE id = '{$message_id}'", true);
		
		if ($Messages[0]["parent_id"] && $Messages[0]["parent_id"]!=0) {
			array_merge($Messages, $this->thread($Messages[0]["parent_id"]));	
		}
		return $Messages;
		
	}
	
	function send($user_id, $message_template_id, $variables = array(), $options = array("priority" => "regular")) {
		global $UsersModel;
		global $ContactsModel;
		
		$user_ids = (is_array($user_id)) ? $user_id : array($user_id);
		
		foreach ($user_ids as $id) {
			$user = $UsersModel->find("WHERE id = '{$id}'", false, array("email"));
			$variables["user_id"] = $user_id;
			$this->send_to_email($user["email"], $message_template_id, $variables, $options);
		}	
	}
	
	function send_to_email($email, $message_template_id, $variables = array(), $options = array("priority" => "regular")) {

		$message_template = $this->get_template_data($message_template_id);
		$message_template["subject"] = ($variables["subject"]) ? $variables["subject"] : $message_template["subject"];
		
		$e = new EmailTemplate($message_template["body"], $message_template["subject"]);
		$e->send_from($message_template["from"], $message_template["send_from"]);
		$e->set_variables($variables);
		//$e->send("joey.caughey@gmail.com", "Joey Caughey");
		$e->send($email, $name);
	
		$this->insert(array(
			"parent_id" => 0,
			"sent_to_user_id" => $variables["user_id"],
			"sent_by_user_id" => "",
			"subject" => $e->subject,
			"body" => $e->output
		));
	}
	
	function get_template_data($message_template_id) {
		global $ModuleMailServicesEmailTemplatesModel;
		
		$Template = $ModuleMailServicesEmailTemplatesModel->find("WHERE template_id = '{$message_template_id}'", false);
		
		$message_template["from"] = ($Template["from"]) ? $Template["from"] : "nobody@pooolo.com";
		$message_template["from_name"] = ($Template["from_name"]) ? $Template["from_name"] : "Nobody";
		$message_template["subject"] = ($Template["subject"]) ? $Template["subject"] : "No Subject";
		$message_template["body"] = ($Template["body"]) ? $Template["body"] : "blank";
		
		return $message_template;
	}
	
	
}