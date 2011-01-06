<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbStatsAdmin', 'DbStatsController', true);
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'template', 'moufTemplate');

?>