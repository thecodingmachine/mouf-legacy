<?php


ini_set('display_errors', 1);
// Add E_ERROR to error reporting it it is not already set
error_reporting(E_ERROR | error_reporting());

require_once '../../Mouf.php';

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once 'utils/check_rights.php';

$res = MoufManager::getMoufManager()->findInstances($_REQUEST["class"]);

echo implode("\n", $res);
?>