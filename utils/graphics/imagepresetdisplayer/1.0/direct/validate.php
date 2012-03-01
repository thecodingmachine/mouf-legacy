<?php
// This file validates that a .htaccess file is defined at the root of the project.
// If not, an alert is raised.

// We only include the MoufUniversalParameters.php because that's all we need to access the ROOT_PATH constant.
require_once dirname(__FILE__)."/../../../../../../Mouf.php";

$jsonObj = array();
$instances = MoufManager::getMoufManager()->getInstancesList();
$errors = array();
foreach ($instances as $instanceName => $instanceClass) {
	if ($instanceClass == "StaticImageDisplayer") {
		$instance = MoufManager::getMoufManager()->getInstance($instanceName);
		/* @var $instance StaticImageDisplayer */
		$htAccessPath = ROOT_PATH.$instance->savePath;
		if (!file_exists(ROOT_PATH.$instance->savePath.DIRECTORY_SEPARATOR.".htaccess")){
			$errors[] = $instanceName;
		}
	}
}

if (count($errors)){
	$jsonObj['code'] = "error";
	$instanceNames = implode("|", $errors);
	$jsonObj['html'] = ".htaccess files are missing for your Static diplayer instances <a href='".ROOT_URL."plugins/utils/graphics/imagepresetdisplayer/1.0/direct/create_htaccess_files.php?instances=$instanceNames'>Create them</a>";
}else{
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "No .htaccess files missing";
}
echo json_encode($jsonObj);
exit;