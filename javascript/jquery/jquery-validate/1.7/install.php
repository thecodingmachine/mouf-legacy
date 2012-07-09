<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

$jQueryValidateLib = $moufManager->createInstance("WebLibrary");
$jQueryValidateLibName = InstallUtils::getInstanceName("jQueryUi", $moufManager);
$jQueryValidateLib->setName($jQueryValidateLibName);
$jQueryValidateLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-validate/1.7/jquery.validate.min.js'
));
$jQueryValidateLib->getProperty("renderer")->setValue($renderer);
$jQueryValidateLib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQuery')));

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();