<?php
MoufUtils::registerMainMenu('utilsMainMenu', 'Utils', null, 'mainMenu', 200);
MoufUtils::registerMenuItem('utilsDbMailServiceInterfaceMenu', 'DB Mail Service', null, 'utilsMainMenu', 30);
MoufUtils::registerMenuItem('utilsDbMailServiceInterfaceViewOutgoingMailsMenuItem', 'View outgoing mails', 'javascript:chooseInstancePopup("DBMailService", "'.ROOT_URL.'mouf/dbmailservice/?instanceName=", "'.ROOT_URL.'")', 'utilsDbMailServiceInterfaceMenu', 10);


// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbmailserviceinstall', 'DBMailServiceInstallController', true);
MoufManager::getMoufManager()->bindComponents('dbmailserviceinstall', 'template', 'installTemplate');

MoufManager::getMoufManager()->declareComponent('dbmailservice', 'DBMailServiceListController', true);
MoufManager::getMoufManager()->bindComponents('dbmailservice', 'template', 'moufTemplate');
?>