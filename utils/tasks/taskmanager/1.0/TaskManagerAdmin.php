<?php

MoufUtils::registerMenuItem('taskManagerSubMenuItem', 'Task Manager', null, 'moufSubMenu', 60);
MoufUtils::registerMenuItem('taskManagerInstall2MenuItem', 'Install Task Manager', 'mouf/taskManager/', 'taskManagerSubMenuItem', 10);
MoufUtils::registerMenuItem('taskManagerViewAwaitingTasks2MenuItem', 'View awaiting tasks', 'mouf/taskManager/viewAwaitingTasks', 'taskManagerSubMenuItem', 20);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('taskManager', 'TaskManagerController', true);
MoufManager::getMoufManager()->bindComponents('taskManager', 'template', 'moufTemplate');
?>