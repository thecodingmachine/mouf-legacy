<?php
MoufUtils::registerMainMenu('dbMainMenu', 'DB', null, 'mainMenu', 70);
MoufUtils::registerMenuItem('dbSqlDatasourceAdminSubMenu', 'SQL Datasource', null, 'dbMainMenu', 60);
MoufUtils::registerMenuItem('dbSqlDatasourceAdminGenerateColumnBaseSubMenu', 'Generate column beans', 'javascript:chooseInstancePopup("SqlDataSource", "'.ROOT_URL.'mouf/datasourceadmin/?name=", "'.ROOT_URL.'")', 'dbSqlDatasourceAdminSubMenu', 60);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('datasourceadmin', 'MoufDatasourceInstanceController', true);
MoufManager::getMoufManager()->bindComponents('datasourceadmin', 'template', 'moufTemplate');

?>