<?php
// FIXME: it is absolutely very important that this file is protected!
// We are returning the entire config of the app to anyone.
// This is a serious security hazard.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	$fileName = dirname(__FILE__)."/../../config.php";
} else {
	$fileName = dirname(__FILE__)."/../../mouf/config.php";
}


$constants_list = null;

// If no config file exist, there is no constants defined. Let's return an empty list.
if (!file_exists($fileName)) {
	echo serialize(array());
	exit;
}

require_once $fileName;

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]="json") {
	$encode = "json";
}

$allConstants = get_defined_constants(true);

// No custom constants? Let's return an empty list.
if (!isset($allConstants['user'])) {
	echo serialize(array());
	exit;
}

// Some custom constants? They come from config.php.
// Let's return those.
//echo serialize($allConstants['user']);

if ($encode == "php") {
	echo serialize($allConstants['user']);
} elseif ($encode == "json") {
	echo json_encode($allConstants['user']);
} else {
	echo "invalid encode parameter";
}

?>