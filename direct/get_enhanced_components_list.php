<?php
/**
 * Returns a serialized string representing the array for all components declarations (classes with the @Component annotation),
 * along additional interesting infos (subclasses, name of the declaration file, etc...)
 */

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../Mouf.php';
} else {
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	require_once '../MoufAdmin.php';
}
require_once '../Moufspector.php';

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