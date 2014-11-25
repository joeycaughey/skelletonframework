<?php
global $ModuleScollarsModel;
global $ModuleContestContestantsModel;
global $UsersModel;

if ($ModuleScollarsModel->ACTIVE_SCOLLAR() && $_POST) {
	if ($_POST["action"]) {
		if ($_POST["action"]=="join" || $_POST["action"]=="pledge") {
			$ModuleContestContestantsModel->insert(array("contest_id" => $_POST["contest_id"], "contestant_id" => $ModuleContestantsModel->ACTIVE_CONTESTANT["id"]));
		} else if ($_POST["action"]=="epic") {
			if ($UsersModel->ACTIVE_USER()) {
				$ModuleContestantEpicVotesModel->insert_if_doesnt_exist(array("contestant_id" => $_POST["contestant_id"], "user_id" => $UsersModel->ACTIVE_USER()), array("contestant_id", "user_id"));
			}
		}
		
	} else {
	
		$ModuleScollarsModel->update($_POST, $ModuleScollarsModel->ACTIVE_SCOLLAR["id"]);
	}
} 