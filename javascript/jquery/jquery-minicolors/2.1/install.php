<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("jQueryMiniColors")) {
	$jQueryMiniColorsLib = $moufManager->getInstanceDescriptor("jQueryMiniColors");
} else {
	$jQueryMiniColorsLib = $moufManager->createInstance("WebLibrary");
	$jQueryMiniColorsLib->setName("jQueryMiniColors");
}
$jQueryMiniColorsLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-minicolors/2.1/jquery.miniColors.min.js'
));
$jQueryMiniColorsLib->getProperty("cssFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-minicolors/2.1/jquery.miniColors.css'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryMiniColorsLib->getProperty("renderer")->setValue($renderer);
$jQueryMiniColorsLib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQueryLibrary')));

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryMiniColorsLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>