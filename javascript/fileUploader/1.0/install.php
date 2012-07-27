<?php
// First, let's request the install utilities
require_once '../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("fileUploaderLibrary")) {
	$fileUploaderLib = $moufManager->getInstanceDescriptor("fileUploaderLibrary");
} else {
	$fileUploaderLib = $moufManager->createInstance("WebLibrary");
	$fileUploaderLib->setName("fileUploaderLibrary");
}
$fileUploaderLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/fileUploader/1.0/fileuploader.js'
));
$fileUploaderLib->getProperty("cssFiles")->setValue(array(
	'plugins/javascript/fileUploader/1.0/fileuploader.css'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$fileUploaderLib->getProperty("renderer")->setValue($renderer);

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $fileUploaderLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();