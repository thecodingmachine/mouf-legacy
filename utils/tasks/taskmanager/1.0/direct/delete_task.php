<?php
/**
 * Deletes the task "id" from task manager "taskmanager".
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

$id = (int) $_REQUEST["id"];
$taskmanager = $_REQUEST["taskmanager"];
if (get_magic_quotes_gpc()==1)
{
	$taskmanager = stripslashes($taskmanager);
}


$moufManager = MoufManager::getMoufManager();

$taskManager = $moufManager->getInstance($taskmanager);
/* @var $taskManager TaskManager */

$taskManager->disableTask($taskManager->getTask($id));

?>