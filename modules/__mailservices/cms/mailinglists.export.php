<?php
global $ModuleMailingListsModel;
global $ModuleMailingListsContactsModel;

$MailingList = $ModuleMailingListsModel->find("WHERE id = '{$_GET["id"]}'");
$Contacts = $ModuleMailingListsContactsModel->find("WHERE mailinglist_id = '{$MailingList["id"]}'", true);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"mailinglist-".FORMAT_forurl($MailingList["title"]).".csv\"");

$keys = false;
foreach($Contacts as $contact) {
	$data = $contact;
 	$data = array_merge($data, $contact["contact"]);
 	$data = array_merge($data, $contact["address"]);
 	unset($data["id"]);
 	unset($data["contact_id"]);
 	unset($data["contact"]);
 	unset($data["address_id"]);
 	unset($data["address"]);
 	unset($data["mailinglist_id"]);
 	unset($data["company_id"]);
 	unset($data["client_id"]);
 	unset($data["province_state_id"]);
 	unset($data["country_id"]);
 	unset($data["position"]);
 	unset($data["ichat"]);
	unset($data["verification_code"]);
	unset($data["verified"]);
	unset($data["date"]);
	$data["date_added"] = date("M-d-Y", $data["date_added"]);
	if (!$keys) {
		$final_keys = array();
		foreach($data as $key => $v) {
			$final_keys[] = $key;
		}
		echo implode(", ", $final_keys)."\n";
		$keys = true;
	}
	echo implode(", ", $data)."\n";
}
?>