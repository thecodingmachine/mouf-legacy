<?php

require_once '../../Mouf.php';
require_once '../Moufspector.php';

$res = MoufManager::getMoufManager()->findInstances($_REQUEST["class"]);


?>