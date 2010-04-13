<?php
// First thing first, let's include the Mouf configuration:
// (only if we are not in admin mode)
require_once dirname(__FILE__).'/../../../../mouf/MoufManager.php';
if (!MoufManager::hasHiddenInstance()) {
	require_once dirname(__FILE__).'/../../../../Mouf.php';
}

$splash = MoufManager::getMoufManager()->getInstance('splash');

if (!isset($splashUrlPrefix)) {
	$splashUrlPrefix = ROOT_URL;
}

$splash->route($splashUrlPrefix);

/*
$urlAnalyzer = MoufManager::getMoufManager()->getInstance('splashUrlAnalyzer');

// Retrieve the split parts
$controllerName = $urlAnalyzer->getControllerName;

// If the controller name is not specified, then let's find the root controller.
// The root controller by convention is called "rootController".
if ($controllerName == null) {
	// Is there a root controller?
	$controller = MoufManager::getMoufManager()->getInstance("rootController");
	
	if ($controller == null) {
		// There is no root controller!
		// Let's go 404!
		Controller::FourOFour("controller.404.no.root.controller");
	}
} else {
	$controller = $urlAnalyzer->getController();
}


$action = $urlAnalyzer->getAction();
*/

// Building real names for controller and method
/*$controller = $theme.'Controller';
Log::trace($controller." : ".$action);

// TEST 1: file exists
if (file_exists(CONTROLLERS_PATHS."$controller.php")) {
	require_once CONTROLLERS_PATHS."$controller.php";
	// TEST 2: controller exists
	if(class_exists($controller)) {
		$controller_obj = new $controller();
		// TEST 3: instance of Controller:
		if (!$controller_obj instanceof Controller) {
			// "Invalid class";
			Controller::FourOFour("controller.404.class.doesnt.extends.controller");
		}

		// Let's pass everything to the controller:
		$controller_obj->callAction($action);

	}else {
		Controller::FourOFour("controller.404.wrong.class");
	}
}elseif(file_exists(SPLASH_CONTROLLERS_PATHS."$controller.php")) {
	require_once SPLASH_CONTROLLERS_PATHS."$controller.php";
	// TEST 2: controller exists
	if(class_exists($controller)) {
		$controller_obj = new $controller();
		// TEST 3: instance of Controller:
		if (!$controller_obj instanceof Controller) {
			// "Invalid class";
			Controller::FourOFour("controller.404.class.doesnt.extends.controller");
		}

		// Let's pass everything to the controller:
		$controller_obj->callAction($action);

	}else {
		Controller::FourOFour("controller.404.wrong.class");
	}
}else {
	Controller::FourOFour("controller.404.wrong.file");
}
*/
?>