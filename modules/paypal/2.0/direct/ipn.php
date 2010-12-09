<?php
require_once '../../../../../Mouf.php';

try {
	$instanceName = get('instance_name');

	/* @var $paypalService PaypalService */
	$paypalService = MoufManager::getMoufManager()->getInstance($instanceName);
	$paypalService->ipn();
} catch (Exception $e) {
	error_log("An error occured in the Paypal IPN: ".$e->getMessage());
	throw $e;
}
?>