<?php
// First, let's request the install utilities
require_once '../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instances
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("javascript.underscore.debug")) {
	$instance = $moufManager->createInstance("WebLibrary");
	$instance->setName("javascript.underscore.debug");
	$instance->getProperty("jsFiles")->setValue(array("plugins/javascript/underscore/1.3.3/lib/underscore.js"));	
}
if (!$moufManager->instanceExists("javascript.underscore")) {
	$instance = $moufManager->createInstance("WebLibrary");
	$instance->setName("javascript.underscore");
	$instance->getProperty("jsFiles")->setValue(array("plugins/javascript/underscore/1.3.3/lib/underscore-min.js"));
}

if (!$moufManager->instanceExists("defaultWebLibraryManager")) {
	$webLibraryManager = $moufManager->getInstanceDescriptor("defaultWebLibraryManager");
	$values = $webLibraryManager->getProperty("webLibraries")->getValue();
	$values[] = $instance;
	$webLibraryManager->getProperty("webLibraries")->setValue($values);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>