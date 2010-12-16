<?php
// This file validates that there are no awaiting tasks in error.
// If so, an alert is raised.


if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../../Mouf.php';
	$selfedit = "false";
} else {
	require_once '../../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../../MoufUniversalParameters.php';
	require_once '../../../../../../mouf/MoufAdmin.php';
	$selfedit = "true";
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../../mouf/direct/utils/check_rights.php';

$moufManager = MoufManager::getMoufManager();

$instanceNames = $moufManager->findInstances("TaskManager");
$result = array();

$nbTasksInErrorByTaskManager = array();


foreach ($instanceNames as $instanceName) {
	/* @var $taskManager TaskManager */
	$taskManager = $moufManager->getInstance($instanceName);
	
	$nb = $taskManager->getNbTasksInError();
	if ($nb != 0) {
		$nbTasksInErrorByTaskManager[$instanceName] = $nb;
	}
	
}

$jsonObj = array();

if (empty($nbTasksInErrorByTaskManager)) {
        $jsonObj['code'] = "ok";
        $jsonObj['html'] = "There are no pending tasks in error.";
} else {
        $jsonObj['code'] = "warn";
        $html = "";
        foreach ($nbTasksInErrorByTaskManager as $instanceName=>$nbErrors) {
	        $html .= "There are <a href='".ROOT_URL."mouf/taskManager/viewAwaitingTasks?selfedit=".$selfedit."'><b>'".$nbErrors."'</b> tasks in error</a> for task manager  '".$instanceName."'<br/>";
        }
        $jsonObj['html'] = $html;
}

echo json_encode($jsonObj);
exit;

?>