<?php

MoufManager::getMoufManager()->declareComponent('splashViewUrls', 'SplashViewUrlsController', true);
MoufManager::getMoufManager()->bindComponent('splashViewUrls', 'template', 'moufTemplate');

MoufUtils::registerMenuItem('splashSubMenu', 'Splash MVC', null, 'moufSubMenu', 45);
MoufUtils::registerMenuItem('splashAdminUrlsListMenuItem', 'View URLs', 'mouf/splashViewUrls/', 'splashSubMenu', 10);

?>