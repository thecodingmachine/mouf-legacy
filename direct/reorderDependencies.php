<?php
// Rewrites the MoufRequire file from the MoufComponents file, and the admin too.


ini_set('display_errors', 1);
// Add E_ERROR to error reporting it it is not already set
error_reporting(E_ERROR | error_reporting());

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
require_once 'utils/check_rights.php';

MoufManager::getMoufManager()->reorderPackagesDependencies();
MoufManager::getMoufManager()->rewriteMouf();

header("Location:../validate?selfedit=".(isset($_REQUEST["selfedit"])?$_REQUEST["selfedit"]:"false"));
