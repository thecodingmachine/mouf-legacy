<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// First, let's request the install utilities
require_once '../../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();
if (!$moufManager->instanceExists("errorLogLogger")) {
	$moufManager->declareComponent("errorLogLogger", "ErrorLogLogger");
	$moufManager->setParameter("errorLogLogger", "level", 4);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
?>