<?php 
require_once('Mouf_DBConnection.php');

/**
 * A class wrapping a connection to a PgSQL database through PDO, with additional goodies (introspection support)
 *
 * @Component
 */
class DB_PgSqlConnection extends Mouf_DBConnection {
	
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
	//public $charset;
	
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
		$dsn = "pgsql:host=".$this->host.";dbname=".$this->dbname.";";
		if (!empty($this->port)) {
			$dsn .= "port=".$this->port.";";
		}
		/*$charset = $this->charset;
		if (empty($charset)) {
			$charset = "UTF-8";
		}
		$dsn .= "charset=".$charset.";";
		*/
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
	 * Returns Root Sequence Table for $table_name
	 * i.e. : if "man" table inherits "human" table , returns "human" for Root Sequence Table
	 * !! Warning !! Child table must share Mother table's primary key
	 * @param string $table_name
	 * @return string
	 */
	public function findRootSequenceTable($table_name){
		
		$child_table = $table_name;
		$root_table = $table_name;
		while ($parent_table=$this->getParentTable($child_table)) {
			$root_table = $parent_table;
			$child_table = $parent_table;
		}
		if ($root_table != null)
		return $root_table;
	
	}
	
	/**
	 * Returns the parent table (if the table inherits from another table).
	 * For DB systems that do not support inheritence, returns the table name.
	 *
	 * @param string $table_name
	 * @return string
	 */
	public function getParentTable($table_name){
		$sql = "SELECT par.relname as parent_table FROM pg_class tab
				Left JOIN pg_inherits inh  ON inh.inhrelid = tab.oid 
				left JOIN pg_class par ON inh.inhparent = par.oid 
				WHERE tab.relname='$table_name'";

		$result = $this->db->getCol($sql);
		if (count($result)==1) {
			$result = $result[0];
		}elseif (count($result)==0){
			$result = null;
		}else{
			throw new TDBM_Exception('Several parents found for table '.$table_name.'<br />\n
					-> Error : this behavior is not managed by TDBM.');
		}
		return $result;
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
			$sql = "SELECT t1.relname AS table2, c1.attname AS col2, c2.attname AS col1 FROM
				pg_attribute c2 JOIN pg_class t2 JOIN
				(pg_constraint con JOIN 
				(pg_class t1 JOIN pg_attribute c1 ON t1.oid = c1.attrelid)
				ON con.conrelid = t1.oid AND con.conkey[1]=c1.attnum)
				ON t2.oid = con.confrelid ON c2.attrelid = t2.oid AND con.confkey[1]=c2.attnum
			WHERE t2.relname='$table_name' AND c2.attname='$column_name'";
		}
		else
		{
			$sql = "SELECT t1.relname AS table2, c1.attname AS col2, c2.attname AS col1 FROM
				pg_attribute c2 JOIN pg_class t2 JOIN
				(pg_constraint con JOIN 
				(pg_class t1 JOIN pg_attribute c1 ON t1.oid = c1.attrelid)
				ON con.conrelid = t1.oid AND con.conkey[1]=c1.attnum)
				ON t2.oid = con.confrelid ON c2.attrelid = t2.oid AND con.confkey[1]=c2.attnum
			WHERE t2.relname='$table_name'";
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
	function getConstraintsFromTable($table_name,$column_name=false) {
		if ($column_name)
		{
			$sql = "SELECT t2.relname AS table1, c2.attname AS col1, c1.attname AS col2 FROM
				pg_attribute c2 JOIN pg_class t2 JOIN
				(pg_constraint con JOIN 
				(pg_class t1 JOIN pg_attribute c1 ON t1.oid = c1.attrelid)
				ON con.conrelid = t1.oid AND con.conkey[1]=c1.attnum)
				ON t2.oid = con.confrelid ON c2.attrelid = t2.oid AND con.confkey[1]=c2.attnum
			WHERE t1.relname='$table_name' AND c1.attname='$column_name'";
		}
		else
		{
			$sql = "SELECT t2.relname AS table1, c2.attname AS col1, c1.attname AS col2 FROM
				pg_attribute c2 JOIN pg_class t2 JOIN
				(pg_constraint con JOIN 
				(pg_class t1 JOIN pg_attribute c1 ON t1.oid = c1.attrelid)
				ON con.conrelid = t1.oid AND con.conkey[1]=c1.attnum)
				ON t2.oid = con.confrelid ON c2.attrelid = t2.oid AND con.confkey[1]=c2.attnum
			WHERE t1.relname='$table_name'";
		}

		$result = $this->getAll($sql);

		return $result;
	}
	
	/**
	 * Returns an array of columns that are declared to be primary keys for this table.
	 *
	 * @param string $table_name the table name
	 * @return array<DB_Column> an array of the primary key columns of the table
	 */
	public function getPrimaryKey($table_name) {
		// TODO: CHANGE RETURN TYPE FOR NEW MODEL!
		$sql = "SELECT col.attname FROM pg_attribute col JOIN pg_constraint c JOIN pg_class t ON c.conrelid = t.oid ON c.conkey[1] = col.attnum AND col.attrelid = t.oid WHERE c.contype='p' AND relname='$table_name'";

		$result = $this->getCol($sql);
		return $result;
	}
	
	/**
	 * Creates a new table in the database.
	 *
	 * @param DB_Table $table The table to create
	 * @param boolean $dropIfExist whether the table should be dropped or not if it exists.
	 */
	public function createTable(DB_Table $table, $dropIfExist = false) {
		throw new Exception("Method not implemented yet");
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
		throw new Exception("Method not implemented yet");
	}
	
	/**
	 * Returns the next Id from the sequence.
	 *
	 * @param string $seq_name The name of the sequence
	 * @param boolean $onDemand If true, if the sequence does not exist, it will be created.
	 * @return int The next value of the sequence
	 */
	public function nextId($seq_name, $onDemand = true) {
		throw new Exception("Not implemented yet");
		
	}
	
    /**
     * Creates a sequence with the name specified.
     * Note: The name is transformed be the getSequenceName method.
     * By default, if "mytable" is passed, the name of the sequence will be "mytable_pk_seq".
     *
     * @param string $seq_name
     */
    public function createSequence($seq_name) {
    	throw new Exception("Not implemented yet");
    }
	
    /**
	 * Returns a table object (DB_Table) from the database. 
	 *
	 * @param string $tableName
	 * @return DB_Table
	 */
	public function getTableFromDbModel($tableName) {
		throw new Exception("Not implemented yet");
	}
	
	/**
     * Returns true if the underlying database is case sensitive, or false otherwise.
     *
     * @return bool
     */
	public function isCaseSensitive() {
		// Pgsql is not case sensitive. Always.
		return false;
	}
	
	/**
     * Checks if the database with the given name exists.
     * Returns true if it exists, false otherwise.
     * Of course, a connection must be established for this call to succeed.
     * Please note that you can create a connection without providing a dbname.
     * 
     * @param string $dbName
     * @return bool
     */
    public function checkDatabaseExists($dbName) {
		$dbs = $this->getAll("select * from pg_database");
		foreach ($dbs as $db_name)
		{
			if (strtolower($db_name['datname'])==$dbName)
				return true;
		}
		return false;
	}
	
	/**
	 * Sets the sequence to the passed value.
	 *
	 * @param string $seq_name
	 * @param unknown_type $id
	 */
	public function setSequenceId($table_name, $id) {
		$seq_name = $this->getSequenceName($table_name);
		
		$this->exec("SELECT setval('$seq_name', '$id')");
	}
	
}


?>