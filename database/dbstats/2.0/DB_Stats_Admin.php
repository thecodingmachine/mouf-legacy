<?php
MoufUtils::registerMainMenu('dbMainMenu', 'DB', null, 'mainMenu', 70);
MoufUtils::registerMenuItem('dbStatsAdminSubMenu', 'DB stats', null, 'dbMainMenu', 70);
MoufUtils::registerMenuItem('dbStatsGenerateStatAdminSubMenu', 'Generate stat table', 'javascript:chooseInstancePopup("DB_Stats", "'.ROOT_URL.'mouf/dbStatsAdmin/?name=")', 'dbStatsAdminSubMenu', 10);
MoufUtils::registerMenuItem('dbStatsRecomputeStatAdminSubMenu', 'Recompute stat table', 'javascript:chooseInstancePopup("DB_Stats", "'.ROOT_URL.'mouf/dbStatsAdmin/recomputeForm?name=")', 'dbStatsAdminSubMenu', 20);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbStatsAdmin', 'DbStatsController', true);
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'template', 'moufTemplate');

?>