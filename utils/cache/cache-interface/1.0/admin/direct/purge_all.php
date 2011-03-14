<?php
// This file purges all the caches of any instance implementing the CacheInterface interface.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../../../Mouf.php';
} else {
	require_once '../../../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../../../MoufUniversalParameters.php';
	require_once '../../../../../../../mouf/MoufAdmin.php';
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../../../mouf/direct/utils/check_rights.php';

$moufManager = MoufManager::getMoufManager();
$instances = $moufManager->findInstances("CacheInterface");

foreach ($instances as $instanceName) {
	$cacheService = $moufManager->getInstance($instanceName);
	/* @var $cacheService CacheInterface */
	
	$cacheService->purgeAll();
}

echo serialize(null);
exit;

?>