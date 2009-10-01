<?php
MoufManager::getMoufManager()->declareComponent('baseWidgetAdminLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('baseWidgetAdminLabelMenuItem', 'menuText', '<b>Widgets</b>');
MoufManager::getMoufManager()->setParameter('baseWidgetAdminLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('baseWidgetAdminLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('baseWidgetAdminLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('baseWidgetEditModeMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('baseWidgetEditModeMenuItem', 'menuText', 'Switch edit mode');
MoufManager::getMoufManager()->setParameter('baseWidgetEditModeMenuItem', 'menuLink', 'mouf/baseWidget/editMode');
MoufManager::getMoufManager()->setParameter('baseWidgetEditModeMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('baseWidgetEditModeMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

$actionMenu = MoufManager::getMoufManager()->getInstance("actionMenu");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("baseWidgetAdminLabelMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("baseWidgetEditModeMenuItem");


// Controller declaration
MoufManager::getMoufManager()->declareComponent('baseWidget', 'BaseWidgetController', true);
MoufManager::getMoufManager()->bindComponents('baseWidget', 'template', 'moufTemplate');
?>