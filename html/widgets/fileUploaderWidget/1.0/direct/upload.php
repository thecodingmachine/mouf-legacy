<?php

require_once dirname(__FILE__)."/../../../../../../Mouf.php";

Mouf::getSessionManager()->start();

$uniqueId = $_REQUEST['uniqueId'];

$sessArray = array("path"=>$_REQUEST['path'],
					"fileId"=>$_REQUEST['fileId'],
					"instanceName"=>$_REQUEST['instanceName']);

$targetFile = $sessArray["path"];
$fileName = '';
if(isset($_REQUEST['fileName'])) {
	$fileName = $_REQUEST['fileName'];
}
if(!$fileName)
	$fileName = null;
if (empty($sessArray['instanceName'])) {
	$returnArray['error'] = 'No instance name';
	echo json_encode($returnArray);
	exit;
}
$instance = MoufManager::getMoufManager()->getInstance($sessArray['instanceName']);
		
if(!is_array($_SESSION["mouf_fileupload_autorizeduploads"][$uniqueId])){
	$returnArray['error'] = 'session error';
	echo json_encode($returnArray);
	exit;
}
$diff = array_diff($sessArray, $_SESSION["mouf_fileupload_autorizeduploads"][$uniqueId]);
if(count($diff)){
	$returnArray['error'] = 'session not match';
	echo json_encode($returnArray);
	exit;
}

$targetPath = dirname($targetFile);

$returnArray = array('success'=>'true');

// Initialize the update
$allowedExtensions = json_decode($instance->fileExtensions);
if(!$allowedExtensions) {
	$allowedExtensions = array();
}
// max file size in bytes
$sizeLimit = $instance->sizeLimit;

$uploader = new JsFileUploader($allowedExtensions, $sizeLimit);

$fileName = $uploader->getFileName();
/* @var $instance FileUploaderWidget */
if (is_array($instance->listenersBefore)) {
	foreach ($instance->listenersBefore as $listener) {
		/* @var $listener UploadifyOnUploadInterface */
		$result = $listener->beforeUpload($targetFile, $fileName, $sessArray["fileId"], $instance, $returnArray, $instance->getParams($uniqueId));
		if($result === false) {
			$returnArray = $result;
			break;
		}
	}
}
if (!is_dir($targetFile)) {
	mkdir(str_replace('//','/', $targetFile), 0755, true);
}
if (!isset($returnArray['error'])) {
	
	$returnArray = $uploader->handleUpload($targetFile, $fileName, $instance->replace);
	$targetFile = $uploader->getFileSave(true);
	if (!$returnArray) {
		$returnArray['error'] = 'no return after JSFileUpload';
	}
}

if (is_array($instance->listenersBefore)) {
	foreach ($instance->listenersBefore as $listener) {
		/* @var $listener UploadifyOnUploadInterface */
		$result = $listener->afterUpload($targetFile, $sessArray["fileId"], $instance, $returnArray, $instance->getParams($uniqueId));
		if($result === false) {
			$returnArray = $result;
			break;
		}
	}
}
echo htmlspecialchars(json_encode($returnArray), ENT_NOQUOTES);
