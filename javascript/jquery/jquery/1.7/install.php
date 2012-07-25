<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

if ($moufManager->instanceExists("jQueryLibrary")) {
	$jQueryLibrary = $moufManager->getInstanceDescriptor("jQueryLibrary");
} else {
	$jQueryLibrary = $moufManager->createInstance("WebLibrary");
	$jQueryLibrary->setName("jQueryLibrary");
}
$jQueryLibrary->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery/1.7/jquery-1.7.2.min.js'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryLibrary->getProperty("renderer")->setValue($renderer);

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryLibrary;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();