<?php 
/**
 * This page returns the columns of a request.
 * return: array("column"=>"type")
 */

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../../Mouf.php';
} else {
	require_once '../../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../../MoufUniversalParameters.php';
	require_once '../../../../../../mouf/MoufAdmin.php';
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../../mouf/direct/utils/check_rights.php';


$datasourceName = $_REQUEST["name"];
$datasource = MoufManager::getMoufManager()->getInstance($datasourceName);
/* @var $datasource SqlDataSource */

$sql = $datasource->getRequest();
// FIXME: what if there is no results (empty table)
$rows = $datasource->dbConnection->getAll($sql, PDO::FETCH_ASSOC, null, 0, 1);

$columnsArray = array();
foreach ($rows[0] as $key=>$value) {
	// TODO: infer type.
	$columnsArray[$key] = "string";
}

echo serialize($columnsArray);
?>