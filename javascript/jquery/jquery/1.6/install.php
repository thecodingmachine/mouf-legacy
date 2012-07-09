<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

$jQueryLib = $moufManager->createInstance("WebLibrary");
$jQueryLibName = InstallUtils::getInstanceName("jQuery", $moufManager);
$jQueryLib->setName($jQueryLibName);
$jQueryLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery/1.6/jquery-1.6.min.js'
));
$jQueryLib->getProperty("renderer")->setValue($renderer);

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();