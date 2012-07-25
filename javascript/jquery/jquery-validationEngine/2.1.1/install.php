<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
$defaultLanguageDetection = $moufManager->getInstanceDescriptor('defaultLanguageDetection');

if ($moufManager->instanceExists("jQueryValidationEngineLibrary")) {
	$jQueryValidationEngineLibrary = $moufManager->getInstanceDescriptor("jQueryValidationEngineLibrary");
} else {
	$jQueryValidationEngineLibrary = $moufManager->createInstance("I18nWebLibrary");
	$jQueryValidationEngineLibrary->setName("jQueryValidationEngineLibrary");
}
$jQueryValidationEngineLibrary->getProperty("languageDetection")->setValue($defaultLanguageDetection);
$jQueryValidationEngineLibrary->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-validationEngine/2.1.1/js/jquery.validationEngine.js',
	'plugins/javascript/jquery/jquery-validationEngine/2.1.1/js/languages/jquery.validationEngine-[lang].js'
));
$jQueryValidationEngineLibrary->getProperty("cssFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-validationEngine/2.1.1/css/validationEngine.jquery.css'
));

$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryValidationEngineLibrary->getProperty("renderer")->setValue($renderer);
$jQueryValidationEngineLibrary->getProperty("dependencies")->setValue(array($moufManager->getInstanceDescriptor('jQueryUiLibrary')));

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryValidationEngineLibrary;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();