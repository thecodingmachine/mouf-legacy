<?php
// Install controller declaration
MoufManager::getMoufManager()->declareComponent('advancedmailloggerinstall', 'AdvancedMailLoggerInstallController', true);
MoufManager::getMoufManager()->bindComponents('advancedmailloggerinstall', 'template', 'installTemplate');


MoufUtils::registerMenuItem('advancedLoggerSubMenu', 'AdvancedLogger', null, 'moufSubMenu', 60);
MoufUtils::registerMenuItem('advancedLoggerViewStats2MenuItem', 'View log stats', 'javascript:chooseInstancePopup("AdvancedMailLogger", "'.ROOT_URL.'mouf/advancedlogger/showStats?name=")', 'advancedLoggerSubMenu', 10);
MoufUtils::registerMenuItem('advancedLoggerCron2MenuItem', 'Install advanced logger CRON', 'mouf/advancedlogger/', 'advancedLoggerSubMenu', 20);
MoufUtils::registerMenuItem('advancedLoggerCron2MenuItem', 'Manually send log stats', 'mouf/advancedlogger/triggerMails', 'advancedLoggerSubMenu', 30);


// Controller declaration
MoufManager::getMoufManager()->declareComponent('advancedlogger', 'AdvancedMailLoggerController', true);
MoufManager::getMoufManager()->bindComponents('advancedlogger', 'template', 'moufTemplate');
?>