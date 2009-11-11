<?php

// Packages dependencies
$baseDir = dirname(__FILE__)."/../../../../..";
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_Column.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_Table.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_ConnectionSettingsInterface.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_ConnectionInterface.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_Exception.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/Mouf_DBConnection.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_MySqlConnection.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_PgSqlConnection.php';
require_once $baseDir.'/plugins/database/dbconnection/1.0/DB_CachedConnection.php';
require_once $baseDir.'/plugins/database/tdbm/2.0-alpha/TDBM_Service.php';
require_once $baseDir.'/plugins/utils/cache/cache-interface/1.0/CacheInterface.php';
require_once $baseDir.'/plugins/utils/cache/session-cache/1.0/SessionCache.php';
require_once $baseDir.'/plugins/utils/log/log_interface/1.0/LogInterface.php';
require_once $baseDir.'/plugins/utils/log/errorlog_logger/1.0/ErrorLogLogger.php';

$conn = new DB_MySqlConnection();
$conn->host = "localhost";
//$conn->dbname = "admindeo";
$conn->user = "root";
$conn->connect();

if ($conn->checkDatabaseExists("tdbmunittest")) {
	$conn->dropDatabase("tdbmunittest");
}
$conn->createDatabase("tdbmunittest");

/*

$table = new DB_Table("users");
$table->addColumn(new DB_Column("id","INT",false,null,true,true));
$table->addColumn(new DB_Column("login","VARCHAR(255)"));
$table->addColumn(new DB_Column("password","VARCHAR(255)"));
$conn->createTable($table);
*/
$conn->executeSqlFile("sql/tdbmunittest.sql");

$sessionCache = new SessionCache();
$sessionCache->log = new ErrorLogLogger();

$tdbm = new TDBM_Service();
$tdbm->setCacheService($sessionCache);
$tdbm->setConnection($conn);

$user = $tdbm->getNewObject("users");
$user->login = "admin";
$user->password = "admin";
$user->save();

$users = $tdbm->getObjects("users");
var_dump($users[0]->password);
?>