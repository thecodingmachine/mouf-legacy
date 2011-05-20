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

MoufManager::getMoufManager()->declareComponent('splashGenerateService', 'SplashGenerateService', true);

MoufManager::getMoufManager()->declareComponent('splashApacheConfig', 'SplashAdminApacheConfigureController', true);
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'template', 'moufTemplate');
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'splashGenerateService', 'splashGenerateService');

MoufManager::getMoufManager()->declareComponent('splashinstall', 'SplashInstallController', true);
MoufManager::getMoufManager()->bindComponent('splashinstall', 'template', 'installTemplate');
MoufManager::getMoufManager()->bindComponent('splashinstall', 'splashGenerateService', 'splashGenerateService');

MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashAdminLabelMenuItem");
MoufManager::getMoufManager()->getInstance("actionMenu")->menuItems[] = MoufManager::getMoufManager()->getInstance("splashAdminApacheConfigItem");

MoufManager::getMoufManager()->declareComponent('splashHtaccessValidator', 'MoufBasicValidationProvider', true);
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'name', 'Splash validator');
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'url', "plugins/mvc/splash/3.0/direct/splash_htaccess_validator.php");
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'propagatedUrlParameters', array('selfedit'));
MoufManager::getMoufManager()->getInstance("validatorService")->validators[] = MoufManager::getMoufManager()->getInstance("splashHtaccessValidator");

/*MoufManager::getMoufManager()->declareComponent('splashInstanceValidator', 'MoufBasicValidationProvider', true);
MoufManager::getMoufManager()->setParameter('splashInstanceValidator', 'name', 'Splash validator');
MoufManager::getMoufManager()->setParameter('splashInstanceValidator', 'url', "plugins/mvc/splash/3.0/direct/splash_instance_validator.php");
MoufManager::getMoufManager()->setParameter('splashInstanceValidator', 'propagatedUrlParameters', array('selfedit'));
MoufManager::getMoufManager()->getInstance("validatorService")->validators[] = MoufManager::getMoufManager()->getInstance("splashInstanceValidator");
*/
MoufAdmin::getValidatorService()->registerBasicValidator('Splash validator', 'plugins/mvc/splash/3.0/direct/splash_instance_validator.php');

?>