<?php
MoufUtils::registerMainMenu('utilsMainMenu', 'Utils', null, 'mainMenu', 200);
MoufUtils::registerMenuItem('utilsCacheInterfaceMenu', 'Cache management', null, 'utilsMainMenu', 50);
MoufUtils::registerMenuItem('utilsCacheInterfacePurgeAllCachesMenuItem', 'Purge all caches', 'mouf/purgeCaches/', 'utilsCacheInterfaceMenu', 10);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('purgeCaches', 'PurgeCacheController', true);
MoufManager::getMoufManager()->bindComponents('purgeCaches', 'template', 'moufTemplate');
