<?php
// Controller declaration
MoufManager::getMoufManager()->declareComponent('tdbmadmin', 'TdbmController', true);
MoufManager::getMoufManager()->bindComponents('tdbmadmin', 'template', 'moufTemplate');

?>