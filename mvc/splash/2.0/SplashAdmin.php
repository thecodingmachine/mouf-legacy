<?php

MoufUtils::registerMenuItem('splashSubMenu', 'Splash MVC', null, 'moufSubMenu', 45);
MoufUtils::registerMenuItem('splashAdminApacheConfig2Item', 'Configure Apache redirection', 'mouf/splashApacheConfig/', 'splashSubMenu', 45);

MoufManager::getMoufManager()->declareComponent('splashApacheConfig', 'SplashAdminApacheConfigureController', true);
MoufManager::getMoufManager()->bindComponent('splashApacheConfig', 'template', 'moufTemplate');

?>