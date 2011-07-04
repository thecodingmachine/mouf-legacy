<?php
MoufUtils::registerMainMenu('utilsMainMenu', 'Utils', null, 'mainMenu', 200);
MoufUtils::registerMenuItem('utilsTaskManagerSubMenuItem', 'Task Manager', null, 'utilsMainMenu', 60);
MoufUtils::registerMenuItem('utilsTaskManagerInstall2MenuItem', 'Install Task Manager', 'mouf/taskManager/', 'utilsTaskManagerSubMenuItem', 10);
MoufUtils::registerMenuItem('utilsTaskManagerViewAwaitingTasks2MenuItem', 'View awaiting tasks', 'mouf/taskManager/viewAwaitingTasks', 'utilsTaskManagerSubMenuItem', 20);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('taskManager', 'TaskManagerController', true);
MoufManager::getMoufManager()->bindComponents('taskManager', 'template', 'moufTemplate');
?>