<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('mysqlconnectionedit', 'MySqlConnectionEditController', true);
MoufManager::getMoufManager()->bindComponents('mysqlconnectionedit', 'template', 'moufTemplate');
?>