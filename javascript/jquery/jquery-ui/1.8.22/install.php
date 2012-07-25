<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("jQueryUiLibrary")) {
	$jQueryUILib = $moufManager->getInstanceDescriptor("jQueryUiLibrary");
} else {
	$jQueryUILib = $moufManager->createInstance("WebLibrary");
	$jQueryUILib->setName("jQueryUiLibrary");
}
$jQueryUILib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-ui/1.8.20/js/jquery-ui-1.8.20.custom.min.js'
));
$jQueryUILib->getProperty("cssFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-ui/1.8.20/css/ui-darkness/jquery-ui-1.8.20.custom.css'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryUILib->getProperty("renderer")->setValue($renderer);
$jQueryUILib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQueryLibrary')));

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryUILib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();