<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// This file validates that there are no missing labels in the default labels.
// If not, an alert is raised.

// We only include the MoufUniversalParameters.php because that's all we need to access the ROOT_PATH constant.
require_once dirname(__FILE__)."/../../../../../../MoufUniversalParameters.php";
require_once dirname(__FILE__).'/../MessageFile.php';
require_once dirname(__FILE__).'/../LanguageUtils.php';


LanguageUtils::loadAllMessages();

// The array of messages by message, then by language:
// array(message_key => array(language => message))

$keys = LanguageUtils::getAllKeys();

$missingDefaultKeys = array();

foreach ($keys as $key) {
	$msgs = LanguageUtils::getMessageForAllLanguages($key);
	if (!isset($msgs['default'])) {
		$missingDefaultKeys[] = $key; 
	}	
}



$jsonObj = array();

if (empty($missingDefaultKeys)) {
        $jsonObj['code'] = "ok";
        $jsonObj['html'] = "Default translatation is available for all messages.";
} else {
        $jsonObj['code'] = "warn";
        $html = "A default translation is missing for these messages: ";
        foreach ($missingDefaultKeys as $missingDefaultKey) {
        	$html .= "<a href='".ROOT_URL."mouf/editLabels/editLabel?key=".urlencode($missingDefaultKey)."&language=default&backto=".urlencode(ROOT_URL)."mouf/'>".$missingDefaultKey."</a> ";
        }
        
        $jsonObj['html'] = $html;
}

echo json_encode($jsonObj);
exit;

?>