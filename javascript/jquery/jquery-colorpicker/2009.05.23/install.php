<?php
// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("jQueryColorPicker")) {
	$moufManager->declareComponent("jQueryColorPicker", "ScriptTagWidget");
	$moufManager->setParameter("jQueryColorPicker", "cssFiles", array("plugins/javascript/jquery/jquery-colorpicker/2009.05.23/css/colorpicker.css",
	"plugins/javascript/jquery/jquery-colorpicker/2009.05.23/css/colorpicker-widget.css"));
	$moufManager->setParameter("jQueryColorPicker", "jsFiles", array("plugins/javascript/jquery/jquery-colorpicker/2009.05.23/js/colorpicker.js"));
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>