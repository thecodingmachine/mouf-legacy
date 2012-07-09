<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

$jQueryUILib = $moufManager->createInstance("WebLibrary");
$jQueryUILibName = InstallUtils::getInstanceName("jQueryUi", $moufManager);
$jQueryUILib->setName($jQueryUILibName);
$jQueryUILib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-ui/1.8.20/js/jquery-ui-1.8.20.custom.min.js'
));
$jQueryUILib->getProperty("renderer")->setValue($renderer);
$jQueryUILib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQuery')));

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();