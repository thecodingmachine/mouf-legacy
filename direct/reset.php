<?php
// Rewrites the MoufRequire file from the MoufComponents file, and the admin too.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	//require_once '../../Mouf.php';
	require_once '../../MoufComponents.php';
	require_once '../../MoufUniversalParameters.php';
} else {
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	MoufManager::switchToHidden();
	//require_once '../MoufAdmin.php';
	require_once '../MoufAdminComponents.php';
}

require_once '../MoufPackageManager.php';


MoufManager::getMoufManager()->rewriteMouf();

echo "Rewrite done.";