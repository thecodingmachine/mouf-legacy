<?php
MoufManager::getMoufManager()->declareComponent('cacheInterfaceAdminLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('cacheInterfaceAdminLabelMenuItem', 'menuText', '<b>Cache management</b>');
MoufManager::getMoufManager()->setParameter('cacheInterfaceAdminLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('cacheInterfaceAdminLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('cacheInterfaceAdminLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('cacheInterfacePurgeMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('cacheInterfacePurgeMenuItem', 'menuText', 'Purge all caches');
MoufManager::getMoufManager()->setParameter('cacheInterfacePurgeMenuItem', 'menuLink', 'mouf/purgeCaches/');
MoufManager::getMoufManager()->setParameter('cacheInterfacePurgeMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('cacheInterfacePurgeMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

$actionMenu = MoufManager::getMoufManager()->getInstance("actionMenu");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("cacheInterfaceAdminLabelMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("cacheInterfacePurgeMenuItem");

// Controller declaration
MoufManager::getMoufManager()->declareComponent('purgeCaches', 'PurgeCacheController', true);
MoufManager::getMoufManager()->bindComponents('purgeCaches', 'template', 'moufTemplate');
