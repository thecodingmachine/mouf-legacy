<?php

MoufManager::getMoufManager()->declareComponent('splashGenerateService', 'SplashGenerateService', true);

MoufManager::getMoufManager()->declareComponent('splashApacheConfig', 'SplashAdminApacheConfigureController', true);
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'template', 'moufTemplate');
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'splashGenerateService', 'splashGenerateService');

MoufManager::getMoufManager()->declareComponent('splashinstall', 'SplashInstallController', true);
MoufManager::getMoufManager()->bindComponent('splashinstall', 'template', 'installTemplate');
MoufManager::getMoufManager()->bindComponent('splashinstall', 'splashGenerateService', 'splashGenerateService');

MoufManager::getMoufManager()->declareComponent('splashHtaccessValidator', 'MoufBasicValidationProvider', true);
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'name', 'Splash validator');
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'url', "plugins/mvc/splash/3.2/direct/splash_htaccess_validator.php");
MoufManager::getMoufManager()->setParameter('splashHtaccessValidator', 'propagatedUrlParameters', array('selfedit'));
MoufManager::getMoufManager()->getInstance("validatorService")->validators[] = MoufManager::getMoufManager()->getInstance("splashHtaccessValidator");

MoufAdmin::getValidatorService()->registerBasicValidator('Splash validator', 'plugins/mvc/splash/3.2/direct/splash_instance_validator.php');

MoufUtils::registerMainMenu('mvcMainMenu', 'MVC', null, 'mainMenu', 100);
MoufUtils::registerMenuItem('mvcSplashSubMenu', 'Splash MVC', null, 'mvcMainMenu', 45);
MoufUtils::registerMenuItem('mvcSplashAdminApacheConfig2Item', 'Configure Apache redirection', 'mouf/splashApacheConfig/', 'mvcSplashSubMenu', 45);



?>