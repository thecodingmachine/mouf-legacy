<?php
require_once('../../MoufUniversalParameters.php');

// This validator calls the "analyze_includes" file and returns the result adapted to the display of a validator (JSON format).

$url = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].ROOT_URL."mouf/direct/analyze_includes.php";

if (isset($_REQUEST['selfedit'])) {
	$url .= "?selfedit=".$_REQUEST['selfedit'];
}

$ch = curl_init();
		
curl_setopt( $ch, CURLOPT_URL, $url);

//curl_setopt( $ch, CURLOPT_HEADER, FALSE );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
//curl_setopt( $ch, CURLOPT_POST, TRUE );
curl_setopt( $ch, CURLOPT_POST, FALSE );
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );

$response = curl_exec( $ch );

if( curl_error($ch) ) { 
	throw new Exception("An error occured: ".curl_error($ch));
}
curl_close( $ch );

// Let's strip the invalid parts:
$arr = explode("\nX4EVDX4SEVX548DSVDXCDSF489\n", $response);
if (count($arr) < 2) {
	// No delimiter: there has been a crash.
	$obj = array("errorType"=>"crash", "errorMsg"=>$response);
} else {
	$msg = $arr[count($arr)-1]; 
	
	// Disable E_NOTICE because unserialize creates a E_NOTICE if unserialize fails.
	//error_reporting(error_reporting() | !E_NOTICE);
	error_reporting(E_ERROR);
	$obj = unserialize($msg);
}

if ($obj === false) {
	$jsonObj['code'] = "error";
	$jsonObj['html'] = "Error while running the required files validator:<br/> ".$msg;
	echo json_encode($jsonObj);
	exit;
}

$jsonObj = array();
if (isset($obj['errorType'])) {
	$jsonObj['code'] = "error";
	$jsonObj['html'] = "Error while running the required files validator:<br/> ".$obj['errorMsg'];
} else {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "PHP included files: OK";
}

echo json_encode($jsonObj);

?>