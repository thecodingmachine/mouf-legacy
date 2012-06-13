<?php
require_once dirname(__FILE__).'/../../../../../../Mouf.php';

$diplayerInstance = $_GET['instance'];
$sourceFileName = $_GET['url'];

error_log("file not found :: $sourceFileName");

$imageDisplay = MoufManager::getMoufManager()->getInstance($diplayerInstance);

$imageDisplay->sourceFileName = $sourceFileName;
$imageDisplay->outputImage();
