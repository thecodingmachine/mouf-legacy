<?php

if ($_REQUEST["selfedit"]!="true") {
	//echo "mouf";
	require_once '../../Mouf.php';
} else {
	//echo "moufadmin";
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../MoufAdmin.php';
}
require_once '../Moufspector.php';

//$res = MoufManager::getMoufManager()->findInstances($_REQUEST["class"]);
$class = new MoufReflectionClass($_REQUEST["class"]);
echo $class->toXml();

?>