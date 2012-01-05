<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("errorLogLogger")) {
	
	$errorLogLogger = $moufManager->createInstance("ErrorLogLogger");
	// Let's set a name for this instance (otherwise, it would be anonymous)
	$errorLogLogger->setName("errorLogLogger");
	$errorLogLogger->getProperty("level")->setValue(4);
	
	/*$moufManager->declareComponent("errorLogLogger", "ErrorLogLogger");
	$moufManager->setParameter("errorLogLogger", "level", 4);*/
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>