<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instances
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("defaultWebLibraryRenderer")) {
	$moufManager->declareComponent("defaultWebLibraryRenderer", "DefaultWebLibraryRenderer");
}
if (!$moufManager->instanceExists("defaultWebLibraryManager")) {
	$moufManager->declareComponent("defaultWebLibraryManager", "WebLibraryManager");
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>