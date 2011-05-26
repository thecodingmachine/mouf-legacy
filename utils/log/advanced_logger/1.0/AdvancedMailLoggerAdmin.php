<?php
// Install controller declaration
MoufManager::getMoufManager()->declareComponent('advancedmailloggerinstall', 'AdvancedMailLoggerInstallController', true);
MoufManager::getMoufManager()->bindComponents('advancedmailloggerinstall', 'template', 'installTemplate');

// Admin Controller declaration
MoufManager::getMoufManager()->declareComponent('advancedLoggerLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('advancedLoggerLabelMenuItem', 'menuText', '<b>Advanced Logger</b>');
MoufManager::getMoufManager()->setParameter('advancedLoggerLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('advancedLoggerLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('advancedLoggerLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('advancedLoggerCronMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('advancedLoggerCronMenuItem', 'menuText', 'Install advanced logger CRON');
MoufManager::getMoufManager()->setParameter('advancedLoggerCronMenuItem', 'menuLink', 'mouf/advancedlogger/');
MoufManager::getMoufManager()->setParameter('advancedLoggerCronMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('advancedLoggerCronMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

MoufManager::getMoufManager()->declareComponent('advancedLoggerSendMailMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('advancedLoggerSendMailMenuItem', 'menuText', 'Manually send log stats');
MoufManager::getMoufManager()->setParameter('advancedLoggerSendMailMenuItem', 'menuLink', 'mouf/advancedlogger/triggerMails');
MoufManager::getMoufManager()->setParameter('advancedLoggerSendMailMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('advancedLoggerSendMailMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));


$actionMenu = MoufManager::getMoufManager()->getInstance("actionMenu");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("advancedLoggerLabelMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("advancedLoggerCronMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("advancedLoggerSendMailMenuItem");


// Controller declaration
MoufManager::getMoufManager()->declareComponent('advancedlogger', 'AdvancedMailLoggerController', true);
MoufManager::getMoufManager()->bindComponents('advancedlogger', 'template', 'moufTemplate');
?>