<?php
/**
 * This file should be included at the beginning of each file of the "/direct" folder.
 * It checks that the rights are ok.
 * The user is allowed access to the file if he is logged, or if he is requesting the file from localhost
 * (because it could be a request from Mouf itself via Curl, and therefore not logged).
 */

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
	return;
}

//if (!isset($_SESSION)) {
	session_start();
//}

if (!isset($_SESSION['MoufMoufUserId'])) {
	echo 'Error! You must be logged in to access this screen';
	exit;
}

?>