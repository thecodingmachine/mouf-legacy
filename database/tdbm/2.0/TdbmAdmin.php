<?php
MoufUtils::registerMainMenu('dbMainMenu', 'DB', null, 'mainMenu', 70);
MoufUtils::registerMenuItem('dbTDBMAdminSubMenu', 'DB stats', null, 'dbMainMenu', 80);
MoufUtils::registerMenuItem('dbTDBMGenereateDAOAdminSubMenu', 'Generate DAOs', 'javascript:chooseInstancePopup("TDBM_Service", "'.ROOT_URL.'mouf/tdbmadmin/?name=", "'.ROOT_URL.'")', 'dbTDBMAdminSubMenu', 10);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('tdbmadmin', 'TdbmController', true);
MoufManager::getMoufManager()->bindComponents('tdbmadmin', 'template', 'moufTemplate');

?>