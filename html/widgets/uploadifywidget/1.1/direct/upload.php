<?php 
error_log('test');
if (!isset($_POST['sessionId'])) {
	$returnArray['status'] = 'error';
	$returnArray['msg'] = 'The posted file size exceed the post_max_size php.ini directive value';
	echo json_encode($returnArray);
	exit;
}

session_name($_POST['sessionName']);
session_id($_POST['sessionId']);
//setcookie($_POST['sessionName'], $_POST['sessionId']);
$_COOKIE[$_POST['sessionName']] = $_POST['sessionId'];

require_once dirname(__FILE__)."/../../../../../../Mouf.php";

//error_log("SESSIONNAME". $_POST['sessionName']);
//error_log("SESSIONID". $_POST['sessionId']);
//error_log("SESSIONID from sessionname". session_id());

/*session_id($_POST['sessionId']);*/


Mouf::getSessionManager()->start();

$uniqueId = $_POST['uniqueId'];
//error_log("UNIQUE ID ".$uniqueId);
//error_log("SESSION ".var_export($_SESSION, true));

//error_log(var_export($_SESSION, true));

//if (!isset($_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId])) {
	//throw new Exception("Upload security exception.");
//}
$sessArray = array("path"=>$_POST['path'],
					"fileId"=>$_POST['fileId'],
					"instanceName"=>$_POST['instanceName']);
// $_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId];
$targetFile = $sessArray["path"];
if(!is_array($_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId])){
	$returnArray['status'] = 'error';
	$returnArray['msg'] = 'session error';
	echo json_encode($returnArray);
	exit;
}
$diff = array_diff($sessArray, $_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId]);
if(count($diff)){
	$returnArray['status'] = 'error';
	echo json_encode($returnArray);
	exit;
}
error_log('1.1');
error_log(var_export($sessArray['instanceName'], true));
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	if($_FILES['Filedata']['error']!==UPLOAD_ERR_OK){
		$returnArray['status'] = 'error';
		$returnArray['msg'] = 'The file is not uploaded completely';
		echo json_encode($returnArray);
		exit;
	}
	$uploadedFileName = $_FILES['Filedata']['name'];
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);

	// if (in_array($fileParts['extension'],$typesArray)) {
	// Uncomment the following line if you want to make the directory if it doesn't exist
	$targetPath = dirname($targetFile);

	$returnArray = array('status'=>'ok');
	
	if (!empty($sessArray['instanceName'])) {
		$instance = MoufManager::getMoufManager()->getInstance($sessArray['instanceName']);
error_log(var_export($instance, true));
		
		/* @var $instance UploadifySingleFileWidget */
		if (empty($instance->fileName)) {
			$instance->fileName = $uploadedFileName;
		}
		if (is_array($instance->listeners)) {
			foreach ($instance->listeners as $listener) {
				/* @var $listener UploadifyOnUploadInterface */
				$result = $listener->onUpload($tempFile, $targetFile, $sessArray["fileId"], $instance, $returnArray, $uploadedFileName);
				if (!$result) {
					$returnArray['status'] = 'error';
					break; 
				}
			}
		}
	}
	
	if (!is_dir($targetPath)) {
		mkdir(str_replace('//','/', $targetPath), 0755, true);
	}
	if ($returnArray['status'] != "error") {
		$result = move_uploaded_file($tempFile,$targetFile);
		if (!$result) {
			$returnArray['status'] = 'error';
		}
	}
	
	echo json_encode($returnArray);
	// } else {
	// 	echo 'Invalid file type.';
	// }
}