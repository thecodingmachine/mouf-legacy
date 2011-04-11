<?php
// This validator checks that no installation step is pending.

/*if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../Mouf.php';
	$selfEdit = "false";
} else {*/
	require_once '../../MoufComponents.php';
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	MoufManager::switchToHidden();
	require_once '../MoufAdmin.php';
	$selfEdit = "true";
//}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once 'utils/check_rights.php';

$moufManager = MoufManager::getMoufManager();

$multiStepActionService = $moufManager->getInstance('installService');
/* @var $multiStepActionService MultiStepActionService */



$jsonObj = array();
if (!$multiStepActionService->hasRemainingAction()) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "No pending install actions to execute.";
} else {
	$jsonObj['code'] = "warn";
	$jsonObj['html'] = "An installation process did not complete. Please <a href='".ROOT_URL."mouf/install/?selfedit=".$selfEdit."'>resume the install process</a>.";
}

echo json_encode($jsonObj);
?>