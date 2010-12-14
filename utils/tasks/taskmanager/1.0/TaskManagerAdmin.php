<?php
MoufManager::getMoufManager()->declareComponent('taskManagerLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('taskManagerLabelMenuItem', 'menuText', '<b>Task Manager</b>');
MoufManager::getMoufManager()->setParameter('taskManagerLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('taskManagerLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('taskManagerLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('taskManagerInstallMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('taskManagerInstallMenuItem', 'menuText', 'Install Task Manager');
MoufManager::getMoufManager()->setParameter('taskManagerInstallMenuItem', 'menuLink', 'mouf/taskManager/');
MoufManager::getMoufManager()->setParameter('taskManagerInstallMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('taskManagerInstallMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

MoufManager::getMoufManager()->declareComponent('taskManagerViewAwaitingTasksMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('taskManagerViewAwaitingTasksMenuItem', 'menuText', 'View awaiting tasks');
MoufManager::getMoufManager()->setParameter('taskManagerViewAwaitingTasksMenuItem', 'menuLink', 'mouf/taskManager/viewAwaitingTasks');
MoufManager::getMoufManager()->setParameter('taskManagerViewAwaitingTasksMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('taskManagerViewAwaitingTasksMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));


$actionMenu = MoufManager::getMoufManager()->getInstance("actionMenu");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("taskManagerLabelMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("taskManagerInstallMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("taskManagerViewAwaitingTasksMenuItem");


// Controller declaration
MoufManager::getMoufManager()->declareComponent('taskManager', 'TaskManagerController', true);
MoufManager::getMoufManager()->bindComponents('taskManager', 'template', 'moufTemplate');
?>