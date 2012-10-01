<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("bootstrapLibrary")) {
	$bootstrapLib = $moufManager->getInstanceDescriptor("bootstrapLibrary");
} else {
	$bootstrapLib = $moufManager->createInstance("WebLibrary");
	$bootstrapLib->setName("bootstrapLibrary");
}
$bootstrapLib->getProperty("jsFiles")->setValue(array(
	'plugins/html/utils/bootstrap/1.0/js/bootstrap.min.js'
));
$bootstrapLib->getProperty("cssFiles")->setValue(array(
	'plugins/html/utils/bootstrap/1.0/css/bootstrap.min.css'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$bootstrapLib->getProperty("renderer")->setValue($renderer);
$bootstrapLib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQueryLibrary')));

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $bootstrapLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();