<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('datasourceadmin', 'MoufDatasourceInstanceController', true);
MoufManager::getMoufManager()->bindComponents('datasourceadmin', 'template', 'moufTemplate');

?>