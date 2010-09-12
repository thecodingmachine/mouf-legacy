<?php
// This file validates that a "splash" instance exists.
// If not, an alert is raised.
if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../Mouf.php';
} else {
	require_once '../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../MoufUniversalParameters.php';
	require_once '../../../../../mouf/MoufAdmin.php';
}

$jsonObj = array();

$instanceExists = MoufManager::getMoufManager()->instanceExists('splash');

if ($instanceExists) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "'splash' instance found";
} else {
	$jsonObj['code'] = "warn";
	$jsonObj['html'] = "Unable to find the 'splash' instance. Click here to <a href='".ROOT_URL."mouf/mouf/newInstance?instanceName=splash&instanceClass=Splash'>create an instance of the Splash class named 'splash'</a>.";
}

echo json_encode($jsonObj);
exit;

?>