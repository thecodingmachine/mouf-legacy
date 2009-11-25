<?php
require_once 'PHPUnit/Framework.php';

// Packages dependencies
$localFilePath = dirname(__FILE__)."/../../../../../..";
require_once $localFilePath.'/mouf/../plugins/database/datasource/formatters/1.0/FormatterInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/formatters/1.0/DateFormatter.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/formatters/1.0/TranslationFormatter.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_Column.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_Table.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_ConnectionSettingsInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_ConnectionInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_Exception.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/Mouf_DBConnection.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_MySqlConnection.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_PgSqlConnection.php';
require_once $localFilePath.'/mouf/../plugins/database/dbconnection/1.0/DB_CachedConnection.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/datasourceinterface/2.0/DataSourceColumnInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/datasourceinterface/2.0/DataSourceColumn.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/datasourceinterface/2.0/DataSourceInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/datasourceinterface/2.0/OrderableDataSourceInterface.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/datasourceinterface/2.0/DataSourceException.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/dynamicdatasource/1.0/DynamicDataSource.php';
require_once $localFilePath.'/mouf/../plugins/utils/common/getvars/1.0/tcm_utils.php';
//require_once $localFilePath.'/mouf/../plugins/database/datasource/tdbmdatasource/2.0/DBMDataSource.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/tdbmdatasource/2.0/DataSourceDBColumn.php';
require_once $localFilePath.'/mouf/../plugins/database/datasource/tdbmdatasource/2.0/SqlDataSource.php';


/*

$table = new DB_Table("users");
$table->addColumn(new DB_Column("id","INT",false,null,true,true));
$table->addColumn(new DB_Column("login","VARCHAR(255)"));
$table->addColumn(new DB_Column("password","VARCHAR(255)"));
$conn->createTable($table);
*/




class SqlDataSourceBasicTest extends PHPUnit_Framework_TestCase 
{
	private $conn;
	
	public function setUp() {
		// First step, let's create the database (or recreate the database) and fill it with test data.
		//$error_log = new ErrorLogLogger();
		//$error_log->level = ErrorLogLogger::$DEBUG;
		
		$this->conn = new DB_MySqlConnection();
		$this->conn->host = "localhost";
		//$conn->dbname = "admindeo";
		$this->conn->user = "root";
		$this->conn->connect();
		
		if ($this->conn->checkDatabaseExists("sqldatasourceunittest")) {
			$this->conn->dropDatabase("sqldatasourceunittest");
		}
		$this->conn->createDatabase("sqldatasourceunittest");
		$this->conn->executeSqlFile("sql/datasourceunittest.sql");
		
		
	}
	
	public function testSelectUsers() {
		
		$dataSource = new SqlDataSource();
		$dataSource->dbConnection = $this->conn;
		$dataSource->setSql("SELECT id, login, password FROM users");
		$dataSource->setCountSql("SELECT count(1) FROM users");
		$keyColumn = new DataSourceColumn();
		$keyColumn->setName("id");
		$keyColumn->setType("int");
		$dataSource->setKeyColumn($keyColumn);
		
		$loginColumn = new DataSourceColumn();
		$loginColumn->setName("login");
		$loginColumn->setType("varchar(255)");
		$passwordColumn = new DataSourceColumn();
		$passwordColumn->setName("password");
		$passwordColumn->setType("varchar(255)");
		$dataSource->setColumns(array($loginColumn, $passwordColumn));
		
		var_dump($dataSource->getRows());
		
	}
	
} 

?>