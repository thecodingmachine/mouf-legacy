<?php
MoufManager::getMoufManager()->declareComponent('splashCommonAdminLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('splashCommonAdminLabelMenuItem', 'menuText', '<b>Splash commons</b>');
MoufManager::getMoufManager()->setParameter('splashCommonAdminLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('splashCommonAdminLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('splashCommonAdminLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('splashAdminUrlsListItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('splashAdminUrlsListItem', 'menuText', 'View URLs');
MoufManager::getMoufManager()->setParameter('splashAdminUrlsListItem', 'menuLink', 'mouf/splashViewUrls/');
MoufManager::getMoufManager()->setParameter('splashAdminUrlsListItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('splashAdminUrlsListItem', 'propagatedUrlParameters', array('selfedit'));

MoufManager::getMoufManager()->declareComponent('splashViewUrls', 'SplashViewUrlsController', true);
MoufManager::getMoufManager()->bindComponent('splashViewUrls', 'template', 'moufTemplate');

MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashCommonAdminLabelMenuItem");
MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashAdminUrlsListItem");
?>