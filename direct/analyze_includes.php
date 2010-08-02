<?php 
/**
 * Analyses all included PHP files to detect whether one is not behaving correctly (outputing some text, which is strictly forbidden)
 */

// Disable output buffering
while (ob_get_level() != 0) {
	ob_end_clean();
}


if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	//require_once '../../Mouf.php';
	require_once '../../MoufComponents.php';
	require_once '../../MoufUniversalParameters.php';
	require_once '../../config.php';
	//require_once 'MoufRequire.php';
} else {
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	//require_once '../MoufAdmin.php';
	require_once '../MoufAdminComponents.php';
}
//require_once '../Moufspector.php';
require_once '../MoufPackageManager.php';

foreach (MoufManager::getMoufManager()->getFilesListRequiredByPackages() as $packageFile) {
	require_once ROOT_PATH.$packageFile;
}

// Ok, now, we can start including our files.
foreach (MoufManager::getMoufManager()->getRegisteredIncludeFiles() as $registeredFile) {
	require_once ROOT_PATH.$registeredFile;

	$moufFile=null;
	$moufLine=null;
	$isSent = headers_sent($moufFile, $moufLine);
	
	if ($isSent) {
		$response = array("errorType"=>"outputStarted", "errorMsg"=>"Error! Output started on line ".$moufLine." in file ".$moufFile.", while including file $registeredFile");
		break;
	}
}

// Unique ID that is unlikely to be in the bottom of the message
echo "\nX4EVDX4SEVX548DSVDXCDSF489\n";

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]="json") {
	$encode = "json";
}

if ($encode == "php") {
	echo serialize($response);
} elseif ($encode == "json") {
	echo json_encode($response);
} else {
	echo "invalid encode parameter";
}
