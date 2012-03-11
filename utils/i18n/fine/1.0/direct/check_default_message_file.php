<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// This file validates that a /resources/message.php exists.
// If not, an alert is raised.

// We only include the MoufUniversalParameters.php because that's all we need to access the ROOT_PATH constant.
require_once dirname(__FILE__)."/../../../../../../MoufUniversalParameters.php";

$jsonObj = array();

if (file_exists(ROOT_PATH."resources/message.php")) {
        $jsonObj['code'] = "ok";
        $jsonObj['html'] = "Default message file found";
} else {
        $jsonObj['code'] = "warn";
        $jsonObj['html'] = "Unable to find default message file. You should create one.";
}

echo json_encode($jsonObj);
exit;

?>