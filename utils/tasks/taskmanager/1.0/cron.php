<?php
require_once dirname(__FILE__).'/../../../../../Mouf.php';

// TODO: add support for selfedit.

$moufManager = MoufManager::getMoufManager();

$instanceNames = $moufManager->findInstances("TaskManager");

foreach ($instanceNames as $instanceName) {
	/* @var $taskManager TaskManager */
	$taskManager = $moufManager->getInstance($instanceName);
	$taskManager->runAllTasks();
}