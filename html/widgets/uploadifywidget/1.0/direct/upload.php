<?php 
//setcookie($_POST['sessionName'], $_POST['sessionId']);
$_COOKIE[$_POST['sessionName']] = $_POST['sessionId'];

require_once dirname(__FILE__)."/../../../../../../Mouf.php";

//error_log("SESSIONNAME". $_POST['sessionName']);
//error_log("SESSIONID". $_POST['sessionId']);
//error_log("SESSIONID from sessionname". session_id());

/*session_name($_POST['sessionName']);
session_id($_POST['sessionId']);*/


Mouf::getSessionManager()->start();

$uniqueId = $_POST['uniqueId'];
//error_log("UNIQUE ID ".$uniqueId);
//error_log("SESSION ".var_export($_SESSION, true));

if (!isset($_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId])) {
	throw new Exception("Upload security exception.");
}
$sessArray = $_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId];
$targetFile = $sessArray["path"];


/* @var $uploadWidget UploadifySingleFileWidget */

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];

	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);

	// if (in_array($fileParts['extension'],$typesArray)) {
	// Uncomment the following line if you want to make the directory if it doesn't exist
	$targetPath = dirname($targetFile);
	if (!is_dir($targetPath)) {
		mkdir(str_replace('//','/', $targetPath), 0755, true);
	}
	move_uploaded_file($tempFile,$targetFile);
	if (!empty($sessArray['instanceName'])) {
		$instance = MoufManager::getMoufManager()->getInstance($sessArray['instanceName']);
		/* @var $instance UploadifySingleFileWidget */
		if (is_array($instance->listeners)) {
			foreach ($instance->listeners as $listener) {
				/* @var $listener UploadifyOnUploadInterface */
				$listener->onUpload($tempFile, $targetFile, $fileId, $widget)
			}
		}
	}
	
	echo "1";
	// } else {
	// 	echo 'Invalid file type.';
	// }
}