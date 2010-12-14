<?php
/**
 * Returns the list of all tasks in the format:
 * array("taskManagerInstanceName"=>
 * 		array(	"id"=>...,
 * 				"taskProcessorName"=>...,
 * 				"name"=>...,
 * 				"status"=>...,
 * 				"createdDate"=>...,
 * 				"lastTryDate"=>...,
 * 				"nextTryDate"=>...,
 * 				"nbTries"=>...,
 * 				"lastOutput"=>...)
 * )
 */

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../../Mouf.php';
} else {
	require_once '../../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../../MoufUniversalParameters.php';
	require_once '../../../../../../mouf/MoufAdmin.php';
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../../mouf/direct/utils/check_rights.php';

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]="json") {
	$encode = "json";
}

$moufManager = MoufManager::getMoufManager();

$instanceNames = $moufManager->findInstances("TaskManager");
$result = array();

foreach ($instanceNames as $instanceName) {
	/* @var $taskManager TaskManager */
	$taskManager = $moufManager->getInstance($instanceName);
	$tasks = $taskManager->getAwaitingTasks();
	foreach ($tasks as $task) {
		/* @var $task Task */
		$taskProcessor = $moufManager->getInstance($task->getTaskProcessorName());
		/* @var $taskProcessor TaskProcessorInterface */
		$name = $taskProcessor->getTaskName($task);
		
		$result[$instanceName][] = array('id'=>$task->getId(),
			'taskProcessorName'=>$task->getTaskProcessorName(),
			'name'=>$name,
			'status'=>$task->getStatus(),
			'createdDate'=>$task->getCreatedDate(),
			'lastTryDate'=>$task->getLastTryDate(),
			'nextTryDate'=>$task->getNextTryDate(),
			'nbTries'=>$task->getNbTries(),
			'lastOutput'=>$task->getLastOutput()
			);			
	}
}

if ($encode == "php") {
	echo serialize($result);
} elseif ($encode == "json") {
	echo json_encode($result);
} else {
	echo "invalid encode parameter";
}

?>