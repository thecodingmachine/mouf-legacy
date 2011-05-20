<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if (!$moufManager->instanceExists("enhanceCategoryLogFilter")) {
	$moufManager->declareComponent("enhanceCategoryLogFilter", "EnhanceCategoryLogFilter");
	$moufManager->setParameter("enhanceCategoryLogFilter", "useCategory", "category1");
	$moufManager->setParameter("enhanceCategoryLogFilter", "splitPosition", "30");
}

if (!$moufManager->instanceExists("filterLogger")) {
	$moufManager->declareComponent("filterLogger", "FilterLogger");
	// TODO: provide a default logger
	//$moufManager->bindComponent("filterLogger", "logger", TODO);
	$moufManager->bindComponents("filterLogger", "filters", array("enhanceCategoryLogFilter"));
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>