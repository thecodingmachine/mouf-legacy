<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("jQueryLibrary")) {
	$jQueryLib = $moufManager->getInstanceDescriptor("jQueryLibrary");
} else {
	$jQueryLib = $moufManager->createInstance("WebLibrary");
	$jQueryLib->setName("jQueryLibrary");
}
$jQueryLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery/1.7.2/jquery-1.7.2.min.js'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryLib->getProperty("renderer")->setValue($renderer);

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();