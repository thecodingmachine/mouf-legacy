<?php 
require_once('Mouf_DBConnection.php');

/**
 * A class wrapping a connection to a MySQL database through PDO, with additional goodies (introspection support)
 *
 * @Component
 */
class DB_MySqlConnection extends Mouf_DBConnection {
	
	/**
	 * The host for the database.
	 * This is the IP or the URL of the server hosting the database.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $host;
	
	/**
	 * The port for the database.
	 * Keep empty to use default port.
	 *
	 * @Property
	 * @var int
	 */
	public $port;
	
	/**
	 * Database user to use when connecting.
	 *
	 * @Property
	 * @var string
	 */
	public $user;
	
	/**
	 * Password to use when connecting.
	 *
	 * @Property
	 * @var string
	 */
	public $password;
	
	/**
	 * Charset used to communicate with the database.
	 * The database will translate any string into this charset before sending us the string.
	 * If not set, this will default to UTF-8
	 *
	 * @Property
	 * @var string
	 */
	public $charset;
	
	/**
	 * Whether a persistent connection is used or not.
	 * If this application is used on the web, you should choose yes. The database connection
	 * will not be closed when the script stops and will be reused on the next connection.
	 * This will help improve your application's performance. 
	 *
	 * This defaults to "true"
	 * 
	 * @Property
	 * @var boolean
	 */
	public $isPersistentConnection;
	
	/**
	 * Returns the DSN for this connection.
	 *
	 * @return string
	 */
	public function getDsn() {
		$dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";";
		if (!empty($this->port)) {
			$dsn .= "port=".$this->port.";";
		}
		$charset = $this->charset;
		if (empty($charset)) {
			$charset = "UTF-8";
		}
		$dsn .= "charset=".$charset.";";
		
		return $dsn;
	}

	/**
	 * Returns the username for this connection (if any).
	 *
	 * @return string
	 */
	public function getUserName() {
		return $this->user;
	}

	/**
	 * Returns the password for this connection (if any).
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Returns an array of options to apply to the connection.
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = array();
		if ($isPersistentConnection != "No") {
			$options[PDO::ATTR_PERSISTENT] = true;
		}
		$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		
		// In MySQL, if we are doing UTF8, there is an additional command to run!
		$charset = strtolower($this->charset);
		if (empty($charset)) {
			$charset = "utf-8";
		}		
		if ($charset == 'utf8' || $charset == 'utf-8') {
			// Workaround for a bug in PHP 5.3.0: replace PDO::MYSQL_ATTR_INIT_COMMAND with 1002
			//$options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
			$options[1002] = "SET NAMES utf8";
		}
		
		return $options;
	}
	
	
	/**
	 * Empty constructor
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * Creates a new table in the database.
	 *
	 * @param string $tableName The table name
	 * @param array<Db_Column> $columnsList
	 * @param boolean $dropIfExist whether the table should be dropped or not if it exists.
	 */
	public function createTable($tableName, $columnsList, $dropIfExist) {
		if ($dropIfExist) {
			$sql = "DROP TABLE IF EXISTS `$tableName`;";
			$this->exec($sql);
		}
		
		//$sql = "CREATE TABLE `$tableName` (\n  ID BIGINT NOT NULL AUTO_INCREMENT,";
		$sql = "CREATE TABLE `$tableName` (\n";
		$first = true;
		$primaryKeyList = array();
		foreach ($columnsList as $column) {
			if (!$first) {
				$sql .= ",\n";
			} else {
				$first = false;
			}
			$sql .= "  `".$column->name."` ".$column->type." ";
			if ($column->nullable) {
				$sql .= "NULL";
			} else {
				$sql .= "NOT NULL";
			}
			if ($column->default != null) {
				$sql .= " DEFAULT ".$column->default;
			}
			if ($column->autoIncrement) {
				$sql .= " AUTO_INCREMENT ";
			}
			
			if ($column->isPrimaryKey) {
				$primaryKeyList[] = $column->name;
			}
		}
		
		if (!empty($primaryKeyList)) {
			$sql .= ",\n  PRIMARY KEY (".implode(", ", $primaryKeyList).")";
		}
		//$sql .= ",\n  PRIMARY KEY (ID)";
		
		$sql .= ");\n";
		//echo $sql;
		$this->exec($sql);
	}
	
	/**
	 * Creates a new index in the database.
	 *
	 * @param string $tableName
	 * @param array<string> $columnsList
	 * @param boolean $isUnique whether the index is unique or not.
	 * @param string $indexName The index name, generated if not specified.
	 */
	public function createIndex($tableName, $columnsList, $isUnique, $indexName=null) {
		if ($indexName == null) {
			$indexName = "IDX_".$tableName."_".implode("_", $columnsList); 
		}
		
		// Let's keep the index name short.
		if (strlen($indexName)>40) {
			$newIndexName = substr($indexName, 0, 20);
			$newIndexName .= '_'.md5($indexName);
			$indexName = $newIndexName; 
		}
	
		$sql = "CREATE ";
		$sql .= $isUnique?"UNIQUE ":"";
		$sql .= "INDEX `$indexName` ON `$tableName` (".implode(", ", $columnsList).");";
		$this->exec($sql);
	}
	
	/**
	 * Returns Root Sequence Table for $table_name
	 * i.e. : if "man" table inherits "human" table , returns "human" for Root Sequence Table
	 * !! Warning !! Child table must share Mother table's primary key
	 * @param unknown_type $table_name
	 */
	protected function findRootSequenceTable($table_name){
		return $table_name;
	}
	
	/**
	 * Returns the parent table (if the table inherits from another table).
	 * For DB systems that do not support inheritence, returns the table name.
	 *
	 * @param string $table_name
	 * @return string
	 */
	public function getParentTable($table_name){
		// No inheritance for Mysql
		return $table_name;
	}
	
	/**
	 * Returns the constraints on table "table_name" and column "column_name" if "column_name"is given
	 * this function returns an array of arrays of the form:
	 * ("table2"=>"name of the constraining table", "col2"=>"name of the constraining column", "col1"=>"name
	 * of the constrained column")
	 *
	 * @param string $table_name
	 * @param string $column_name
	 * @return unknown
	 */
	public function getConstraintsOnTable($table_name,$column_name=false) {
		if ($column_name)
		{
			$sql = "SELECT column_name as col1, referenced_table_name as table2, referenced_column_name as col2 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".$this->dsn['database']."' AND TABLE_NAME='$table_name' AND COLUMN_NAME='$column_name' AND REFERENCED_TABLE_NAME IS NOT NULL";
		}
		else
		{
			$sql = "SELECT column_name as col1, referenced_table_name as table2, referenced_column_name as col2 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".$this->dsn['database']."' AND TABLE_NAME='$table_name' AND REFERENCED_TABLE_NAME IS NOT NULL";
		}

		$result = $this->getAll($sql);

		return $result;
	}
	
	/**
	 * Returns the constraints on table "table_name" and column "column_name" if "column_name"is given
	 * this function returns an array of arrays of the form:
	 * ("table1"=>"name of the constrained table", "col1"=>"name of the constrained column", "col2"=>"name
	 * of the constraining column")
	 *
	 * @param string $table_name
	 * @param string $column_name
	 * @return unknown
	 */
	public function getConstraintsFromTable($table_name,$column_name=false) {
		if ($column_name)
		{
			$sql = "SELECT referenced_column_name as col2, table_name as table1, column_name as col1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".$this->dsn['database']."' AND referenced_table_name='$table_name' AND referenced_column_name='$column_name'";
		}
		else
		{
			$sql = "SELECT referenced_column_name as col2, table_name as table1, column_name as col1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".$this->dsn['database']."' AND referenced_table_name='$table_name'";
		}

		$result = $this->getAll($sql);

		return $result;
	}
	
	/**
	 * Returns an array of columns that are declared to be primary keys for this table.
	 *
	 * @param string $table_name the table name
	 * @return array an array of the primary key columns of the table
	 */
	protected function getPrimaryKey($table_name) {

		// TODO tableinfo override!!!!
		$info = $this->db->tableInfo($table_name);
		$col = array();
		foreach ($info as $column)
		{
			if (strpos($column['flags'],'primary_key')!==false )
			{
				$col[] = $column['name'];
			}
		}
		return $col;
	}
	
	/**
	 * Sets the host (DB server URL) for the connection.
	 *
	 * @param string $host
	 */
	public function setHost($host) {
		$this->host = $host;
	}
	
	/**
	 * Sets the DB port for the connection.
	 *
	 * @param int $host
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * Sets the database name we need to connect to.
	 *
	 * @param string $dbName
	 */
	public function setDbName($dbName) {
		$this->dbname = $dbName;
	}
	
	/**
	 * Sets the user name for the connection.
	 *
	 * @param string $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}
	
	/**
	 * Sets the password for the connection.
	 *
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * Returns the next Id from the sequence.
	 *
	 * @param string $seq_name The name of the sequence
	 * @param boolean $onDemand If true, if the sequence does not exist, it will be created.
	 * @return int The next value of the sequence
	 */
	public function nextId($seq_name, $onDemand = true) {
		$seqname = $this->getSequenceName($seq_name);
        //do {
        //$repeat = 0;
        try {
        	$nbAff = $this->exec('UPDATE ' . $seqname
                               . ' SET id = LAST_INSERT_ID(id + 1)');
        } catch (PDOException $e) {
        	if ($e->getCode() == '42S02') {
             // ONDEMAND TABLE CREATION
             $result = $this->createSequence($seq_name);

             return 1;
        	} else {
        		throw $e;	
        	}
        }
            /*$errCode = PDO::errorCode();
            var_dump($errCode);
            exit;
            $this->popErrorHandling();
            if ($result === DB_OK) {
                // COMMON CASE
                $id = @mysqli_insert_id($this->connection);
                if ($id != 0) {
                    return $id;
                }

                // EMPTY SEQ TABLE
                // Sequence table must be empty for some reason,
                // so fill it and return 1
                // Obtain a user-level lock
                $result = $this->getOne('SELECT GET_LOCK('
                                        . "'${seqname}_lock', 10)");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                if ($result == 0) {
                    return $this->mysqliRaiseError(DB_ERROR_NOT_LOCKED);
                }

                // add the default value
                $result = $this->query('REPLACE INTO ' . $seqname
                                       . ' (id) VALUES (0)');
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }

                // Release the lock
                $result = $this->getOne('SELECT RELEASE_LOCK('
                                        . "'${seqname}_lock')");
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                // We know what the result will be, so no need to try again
                return 1;

            } elseif ($ondemand && DB::isError($result) &&
                $result->getCode() == DB_ERROR_NOSUCHTABLE)
            {
                // ONDEMAND TABLE CREATION
                $result = $this->createSequence($seq_name);

                // Since createSequence initializes the ID to be 1,
                // we do not need to retrieve the ID again (or we will get 2)
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                } else {
                    // First ID of a newly created sequence is 1
                    return 1;
                }

            } elseif (DB::isError($result) &&
                      $result->getCode() == DB_ERROR_ALREADY_EXISTS)
            {
                // BACKWARDS COMPAT
                // see _BCsequence() comment
                $result = $this->_BCsequence($seqname);
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                $repeat = 1;
            }
        } while ($repeat);

        return $this->raiseError($result);*/
	}
	
	public function createSequence($seq_name)
    {
        $seqname = $this->getSequenceName($seq_name);
        $res = $this->exec('CREATE TABLE ' . $seqname
                            . ' (id INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,'
                            . ' PRIMARY KEY(id))');
        
        // insert yields value 1, nextId call will generate ID 2
        $this->exec("INSERT INTO ${seqname} (id) VALUES (0)");
    }
	
}


?>