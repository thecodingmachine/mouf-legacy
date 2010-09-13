<?php
//require_once('../../MoufUniversalParameters.php');

// This validator checks that all the config parameters defined are present in the config.php file.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../Mouf.php';
	$selfEdit = "false";
} else {
	require_once '../../MoufComponents.php';
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	MoufManager::switchToHidden();
	require_once '../MoufAdmin.php';
	$selfEdit = "true";
}

$moufManager = MoufManager::getMoufManager();

$configManager = $moufManager->getConfigManager();

$constants = $configManager->getDefinedConstants(); 
$definedConfigConstants = array_keys($constants);

$availableConfigConstants = array_keys($configManager->getConstantsDefinitionArray());

$missingAvailableConstants = array_diff($definedConfigConstants, $availableConfigConstants);

$missingDefinedConstants = array_diff($availableConfigConstants, $definedConfigConstants);

$jsonObj = array();
if (empty($missingDefinedConstants) && empty($missingAvailableConstants)) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "All parameters have been configured in <code>config.php</code>.";
} else {
	if (!empty($missingAvailableConstants)) {
		$jsonObj['code'] = "warn";
		$msg = "Your <code>config.php</code> file contains constants that have not been defined in Mouf.
		It is important to define these parameters, so that you will be reminded to create them in other environments when you deploy your application.
		<ul>";
		foreach ($missingAvailableConstants as $missingAvailableConstant) {
			$msg .= "<li><a href='".ROOT_URL."mouf/config/register?name=".urlencode($missingAvailableConstant)."&value=".urlencode($constants[$missingAvailableConstant])."&defaultvalue=".urlencode($constants[$missingAvailableConstant])."&selfedit=".$selfEdit."'>Define parameter ".$missingAvailableConstant."</a></li>";
		}
		$msg .= "</ul><br/> ";
	}
	if (!empty($missingDefinedConstants)) {
		$jsonObj['code'] = "error";
		$msg .= "Your <code>config.php</code> file is missing one or more parameter. Parameter(s) missing:
		<ul>";
		foreach ($missingDefinedConstants as $missingDefinedConstant) {
			$msg .= "<li>".$missingDefinedConstant."</li>";
		}
		$msg .= "</ul>
		<a href='".ROOT_URL."mouf/config/?selfedit=".$selfEdit."'>Configure those parameters.</a>";
	}
	
	$jsonObj['html'] = $msg;
}

echo json_encode($jsonObj);
?>