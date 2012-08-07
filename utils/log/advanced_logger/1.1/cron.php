<?php
require_once dirname(__FILE__).'/../../../../../Mouf.php';

// TODO: add support for selfedit.

$moufManager = MoufManager::getMoufManager();

$instanceNames = $moufManager->findInstances("AdvancedMailLogger");

foreach ($instanceNames as $instanceName) {
	/* @var $advancedMailLogger AdvancedMailLogger */
	$advancedMailLogger = $moufManager->getInstance($instanceName);
	$advancedMailLogger->sendMail();
}