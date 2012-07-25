<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
$defaultLanguageDetection = $moufManager->getInstanceDescriptor('defaultLanguageDetection');

if ($moufManager->instanceExists("jQueryValidateLibrary")) {
	$jQueryValidateLib = $moufManager->getInstanceDescriptor("jQueryValidateLibrary");
} else {
	$jQueryValidateLib = $moufManager->createInstance("I18nWebLibrary");
	$jQueryValidateLib->setName("jQueryValidateLibrary");
}

$jQueryValidateLib->getProperty("languageDetection")->setValue($defaultLanguageDetection);
$jQueryValidateLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-validate/1.9.0/jquery.validate.min.js',
	'plugins/javascript/jquery/jquery-validate/1.9.0/localization/messages_[lang].js'
));

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

$jQueryValidateLib->getProperty("renderer")->setValue($renderer);
$jQueryValidateLib->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQueryLibrary')));

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryValidateLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();