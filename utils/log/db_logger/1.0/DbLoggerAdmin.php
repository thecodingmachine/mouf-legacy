<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbloggerinstall', 'DbLoggerInstallController', true);
MoufManager::getMoufManager()->bindComponents('dbloggerinstall', 'template', 'installTemplate');
?>