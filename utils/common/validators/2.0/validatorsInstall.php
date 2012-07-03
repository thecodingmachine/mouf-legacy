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

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>