<?php
// Let's load the Mouf file, and the MoufAdmin file.
// The MoufAdmin will replace the Mouf configuration file.
require_once dirname(__FILE__).'/../MoufComponents.php';
require_once dirname(__FILE__).'/../MoufUniversalParameters.php';
MoufManager::switchToHidden();
require_once 'MoufAdmin.php';

$splashUrlPrefix = ROOT_URL."mouf/";
require_once '../plugins/mvc/splash/1.0/splash.php';

?>