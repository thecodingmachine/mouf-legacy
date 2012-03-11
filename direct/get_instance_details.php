<?php
/**
 * Returns a serialized string representing an instance.
 */

ini_set('display_errors', 1);
// Add E_ERROR to error reporting it it is not already set
error_reporting(E_ERROR | error_reporting());

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	//echo "mouf";
	require_once '../../Mouf.php';
} else {
	//echo "moufadmin";
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	require_once '../MoufAdmin.php';
}
require_once '../Moufspector.php';

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once 'utils/check_rights.php';

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]=="json") {
	$encode = "json";
}

$moufManager = MoufManager::getMoufManager();
// FIXME: the getInstanceDescriptor is calling the getClassDescriptor that itself is making a CURL call.
// In this case, the CURL call is not needed since the getInstanceDescriptor and the getClassDescriptor are
// in the same scope.
$instanceDescriptor = $moufManager->getInstanceDescriptor($_REQUEST["name"]);

$response = array();
$instanceArray = array();
// Note: we might send back class data with instance data... this would save one request.
$instanceArray['name'] = $instanceDescriptor->getName();
$instanceArray['class'] = $instanceDescriptor->getClassName();
$instanceArray['properties'] = array();
$classDescriptor = $instanceDescriptor->getClassDescriptor();
$moufProperties = $classDescriptor->getMoufProperties();
foreach ($moufProperties as $propertyName=>$moufProperty) {
	/* @var $moufProperty MoufPropertyDescriptor */
	$instanceArray['properties'][$propertyName] = array();
	//$instanceArray['properties'][$propertyName]['source'] = $moufProperty->getSource();
	$property = $instanceDescriptor->getProperty($propertyName);
	$value = $property->getValue();
	if ($value instanceof MoufInstanceDescriptor) {
		$serializableValue = $value->getName();
	} elseif (is_array($value)) {
		$serializableValue = array_map(function($singleValue) {
			if ($singleValue instanceof MoufInstanceDescriptor) {
				return $singleValue->getName();
			} else {
				return $singleValue;
			}
		}, $value);
	} else {
		$serializableValue = $value;
	}
	$instanceArray['properties'][$propertyName]['value'] = $serializableValue;
	$instanceArray['properties'][$propertyName]['origin'] = $property->getOrigin();
	$instanceArray['properties'][$propertyName]['metadata'] = $property->getMetaData();
}

$response["instances"][$instanceDescriptor->getName()] = $instanceArray;

// Now, let's embed the class and all the parents with this instance.
$classArray = array();
do {
	$classArray[$classDescriptor->getName()] = $classDescriptor->toJson();
	$classDescriptor = $classDescriptor->getParentClass();
} while ($classDescriptor != null);
$response["classes"] = $classArray;

if ($encode == "php") {
	echo serialize($response);
} elseif ($encode == "json") {
	header("Content-type: application/json");
	echo json_encode($response);
} else {
	echo "invalid encode parameter";
}

?>