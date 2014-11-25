<?php
global $CONFIG;
global $ModuleMailingListsContactsModel;

$result = $ModuleMailingListsContactsModel->insert($_POST);

if ($result) {
	echo '<h4>Added to the list!</h4>';
}