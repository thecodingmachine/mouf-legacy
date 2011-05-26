<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('logstatsinstall', 'LogStatsInstallController', true);
MoufManager::getMoufManager()->bindComponents('logstatsinstall', 'template', 'installTemplate');
?>