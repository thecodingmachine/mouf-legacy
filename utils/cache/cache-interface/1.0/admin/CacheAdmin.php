<?php
MoufUtils::registerMenuItem('cacheInterfaceAdminSubMenu', 'Cache management', null, 'moufSubMenu', 50);
MoufUtils::registerMenuItem('cacheInterfacePurgeAllCachesMenuItem', 'Purge all caches', 'mouf/purgeCaches/', 'cacheInterfaceAdminSubMenu', 10);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('purgeCaches', 'PurgeCacheController', true);
MoufManager::getMoufManager()->bindComponents('purgeCaches', 'template', 'moufTemplate');
