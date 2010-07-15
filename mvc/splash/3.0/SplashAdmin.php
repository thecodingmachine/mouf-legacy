<?php
MoufManager::getMoufManager()->declareComponent('splashAdminLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('splashAdminLabelMenuItem', 'menuText', '<b>Splash</b>');
MoufManager::getMoufManager()->setParameter('splashAdminLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('splashAdminLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('splashAdminLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('splashAdminApacheConfigItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('splashAdminApacheConfigItem', 'menuText', 'Configure Apache redirection');
MoufManager::getMoufManager()->setParameter('splashAdminApacheConfigItem', 'menuLink', 'mouf/splashApacheConfig/');
MoufManager::getMoufManager()->setParameter('splashAdminApacheConfigItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('splashAdminApacheConfigItem', 'propagatedUrlParameters', array('selfedit'));

MoufManager::getMoufManager()->declareComponent('splashApacheConfig', 'SplashAdminApacheConfigureController', true);
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'template', 'moufTemplate');

MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashAdminLabelMenuItem");
MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashAdminApacheConfigItem");
?>