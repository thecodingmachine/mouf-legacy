<?php
/**
 * Returns a serialized string representing the array for all components declarations (classes with the @Component annotation),
 * along additional interesting infos (subclasses, name of the declaration file, etc...)
 */

// Disable output buffering
while (ob_get_level() != 0) {
	ob_end_clean();
}

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../Mouf.php';
} else {
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	require_once '../MoufAdmin.php';
}
require_once '../Moufspector.php';

$file=null;
$line=null;
$isSent = headers_sent($file, $line);

if ($isSent) {
	echo "Error! Output started on line ".$line." in file ".$file;
	exit;
}

$type = null;
if (isset($_REQUEST["type"])) {
	$type = $_REQUEST["type"];
}

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]="json") {
	$encode = "json";
}

if ($encode == "php") {
	echo serialize(Moufspector::getEnhancedComponentsList($type));
} elseif ($encode == "json") {
	echo json_encode(Moufspector::getEnhancedComponentsList($type));
} else {
	echo "invalid encode parameter";
}
?>