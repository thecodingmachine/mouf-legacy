<?php
// Let's load the Mouf file, and the MoufAdmin file.
// The MoufAdmin will replace the Mouf configuration file.
if (file_exists(dirname(__FILE__).'/../MoufComponents.php')) {
	require_once dirname(__FILE__).'/../MoufComponents.php';
}
require_once dirname(__FILE__).'/../MoufUniversalParameters.php';

MoufManager::switchToHidden();
require_once 'MoufAdmin.php';
if (isset($_REQUEST['selfedit']) && $_REQUEST['selfedit']=="true") {
	require_once 'MoufAdminUI.php';
} else {
	// Check file existence just to be sure.
	if (file_exists(dirname(__FILE__).'/../MoufUI.php')) {
		require_once dirname(__FILE__).'/../MoufUI.php';
	}
}

$splashUrlPrefix = ROOT_URL."mouf/";
require_once '../plugins/mvc/splash/1.0/splash.php';

?>