<?php 
/**
 * This page generates the DAOS (via direct access)
 * 
 * TODO: protect the access to admins only!!!!
 */
require_once 'utils/dao_generator.php';

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../Mouf.php';
} else {
	require_once '../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../MoufUniversalParameters.php';
	require_once '../../../../mouf/MoufAdmin.php';
}

$tdbmServiceInstanceName = $_REQUEST["name"];
$tdbmService = MoufManager::getMoufManager()->getInstance($tdbmServiceInstanceName);

$daoFactoryClassName = $_REQUEST["daofactoryclassname"];

$dbConnection = $tdbmService->dbConnection;
$daoGenerator = new TDBMDaoGenerator($dbConnection, $daoFactoryClassName);
$xml = $daoGenerator->generateAllDaosAndBeans();
echo $xml->asXml();

?>