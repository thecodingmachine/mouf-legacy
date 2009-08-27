<?php
/**
 * Returns a serialized string representing the array for all components declares (classes with the @Component annotation)
 */

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

echo serialize(Moufspector::getComponentsList());

?>