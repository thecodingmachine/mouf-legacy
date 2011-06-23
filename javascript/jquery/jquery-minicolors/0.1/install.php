<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("jQueryMiniColors")) {
	$moufManager->declareComponent("jQueryMiniColors", "ScriptTagWidget");
	$moufManager->setParameter("jQueryMiniColors", "cssFiles", array("plugins/javascript/jquery/jquery-minicolors/0.1/jquery.miniColors.css"));
	$moufManager->setParameter("jQueryMiniColors", "jsFiles", array("plugins/javascript/jquery/jquery-minicolors/0.1/jquery.miniColors.js"));
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>