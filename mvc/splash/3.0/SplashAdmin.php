<?php

MoufManager::getMoufManager()->declareComponent('splashGenerateService', 'SplashGenerateService', true);

MoufManager::getMoufManager()->declareComponent('splashApacheConfig', 'SplashAdminApacheConfigureController', true);
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'template', 'moufTemplate');
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'splashGenerateService', 'splashGenerateService');

MoufManager::getMoufManager()->declareComponent('splashinstall', 'SplashInstallController', true);
MoufManager::getMoufManager()->bindComponent('splashinstall', 'template', 'installTemplate');
MoufManager::getMoufManager()->bindComponent('splashinstall', 'splashGenerateService', 'splashGenerateService');

MoufUtils::registerMainMenu('mvcMainMenu', 'MVC', null, 'mainMenu', 100);
MoufUtils::registerMenuItem('mvcSplashSubMenu', 'Splash MVC', null, 'mvcMainMenu', 45);
MoufUtils::registerMenuItem('mvcSplashAdminApacheConfig2Item', 'Configure Apache redirection', 'mouf/splashApacheConfig/', 'mvcSplashSubMenu', 45);

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