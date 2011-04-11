<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('tdbmadmin', 'TdbmController', true);
MoufManager::getMoufManager()->bindComponents('tdbmadmin', 'template', 'moufTemplate');

MoufManager::getMoufManager()->declareComponent('tdbminstall', 'TdbmInstallController', true);
MoufManager::getMoufManager()->bindComponents('tdbminstall', 'template', 'installTemplate');
?>