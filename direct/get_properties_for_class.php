<?php

require_once '../../Mouf.php';
require_once '../Moufspector.php';

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once 'utils/check_rights.php';

$res = MoufManager::getMoufManager()->findInstances($_REQUEST["class"]);


?>