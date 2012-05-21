<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("defaultGlobalErrorHandler")) {
	$defaultGlobalErrorHandler = $moufManager->createInstance("GlobalErrorHandler");
	$defaultGlobalErrorHandler->setName("defaultGlobalErrorHandler");
}

if (!$moufManager->instanceExists("errorTextRenderer")) {
	$errorTextRenderer = $moufManager->createInstance("ErrorTextRenderer");
	$errorTextRenderer->setName("errorTextRenderer");
}

if (!$moufManager->instanceExists("errorHtmlRenderer")) {
	$errorHtmlRenderer = $moufManager->createInstance("ErrorHtmlRenderer");
	$errorHtmlRenderer->setName("errorHtmlRenderer");
}
	
if (!$moufManager->instanceExists("toOutputErrorHandler")) {
	$toOutputErrorHandler = $moufManager->createInstance("ToOutputErrorHandler");
	$toOutputErrorHandler->setName("toOutputErrorHandler");
	$toOutputErrorHandler->getProperty("errorRenderer")->setValue($errorHtmlRenderer);
	$toOutputErrorHandler->getProperty("exceptionRenderer")->setValue($errorHtmlRenderer);
}

if (!$moufManager->instanceExists("toPhpErrorLogErrorHandler")) {
	$toPhpErrorLogErrorHandler = $moufManager->createInstance("ToPhpErrorLogErrorHandler");
	$toPhpErrorLogErrorHandler->setName("toPhpErrorLogErrorHandler");
	$toPhpErrorLogErrorHandler->getProperty("errorRenderer")->setValue($errorTextRenderer);
	$toPhpErrorLogErrorHandler->getProperty("exceptionRenderer")->setValue($errorTextRenderer);
}

$defaultGlobalErrorHandler->getProperty("errorHandlers")->setValue(array($toOutputErrorHandler, $toPhpErrorLogErrorHandler));

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>