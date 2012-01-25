<?php
MoufUtils::registerMainMenu('utilsMainMenu', 'Utils', null, 'mainMenu', 200);
MoufUtils::registerMenuItem('utilsDbMailServiceInterfaceMenu', 'DB Mail Service', null, 'utilsMainMenu', 30);
MoufUtils::registerMenuItem('utilsBlackListMailServiceInterfaceViewUnsubscribedMailsMenuItem', 'View unsubscribed mails', 'javascript:chooseInstancePopup("BlackListMailService", "'.ROOT_URL.'mouf/blacklistmailservice/?instanceName=", "'.ROOT_URL.'")', 'utilsDbMailServiceInterfaceMenu', 20);


// Controller declaration
MoufManager::getMoufManager()->declareComponent('blacklistmailserviceinstall', 'BlackListMailServiceInstallController', true);
MoufManager::getMoufManager()->bindComponents('blacklistmailserviceinstall', 'template', 'installTemplate');

MoufManager::getMoufManager()->declareComponent('blacklistmailservice', 'BlackListMailServiceListController', true);
MoufManager::getMoufManager()->bindComponents('blacklistmailservice', 'template', 'moufTemplate');

?>