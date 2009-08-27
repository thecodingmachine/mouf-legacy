<?php
require_once '../../Mouf.php';

$res = MoufManager::getMoufManager()->findInstances($_REQUEST["class"]);

echo implode("\n", $res);
?>