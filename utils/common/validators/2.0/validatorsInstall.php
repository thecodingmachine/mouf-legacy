<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("validatorsTranslateService")) {
	$moufManager->declareComponent("validatorsTranslateService", "FinePHPArrayTranslationService");
	$moufManager->setParameter("validatorsTranslateService", "i18nMessagePath", "plugins/utils/common/validators/2.0/resources/");
	
	if (!$moufManager->instanceExists("validatorsBrowserLanguageDetection")) {
		$moufManager->declareComponent("validatorsBrowserLanguageDetection", "BrowserLanguageDetection");
	}
	
	$moufManager->bindComponentsViaSetter("validatorsTranslateService", "setLanguageDetection", "validatorsBrowserLanguageDetection");
}

//Let's automatically create validators for the components that are not parametized (eg : don't create a MinMaxRangeValidator)...
$classes = array(
		"EmailValidator",
		'NumericValidator{"allowDecimals":true}',
		"RequiredValidator",
		'URLValidator{"allowFtp":true, "allowHttps":true}'
);

InstallUtils::massCreate($classes, $moufManager);

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>