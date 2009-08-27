<?php 

require_once('DB_Column.php');

/**
 * An abstract class representing wrapping a connection to PDO with additional goodies (introspection support)
 *
 */
abstract class Mouf_DBConnection implements DB_ConnectionSettings {

	/**
	 * The database handler. This is an object whose type is PDO.
	 *
	 * @var PDO
	 */
	protected $dbh;
	
	/*public $db;
	public $dsn;
	public $options;*/
	private $commitOnQuit = true;
	private $autoCommit = true;

	public function __construct() {
		//$this->dsn = $dsn;
		/*if (!isset($options['seqname_format']))
		$options['seqname_format'] = '%s_pk_seq';
		if (!isset($options['persistent']))
		$options['persistent'] = 'TRUE';

		// By default, charset for the connection will be UTF-8.
		if (!isset($dsn['charset']))
		$dsn['charset']="UTF8";

		$this->dsn = $dsn;
		$this->options = $options;
		$this->db =& DB::connect($dsn, $options);
		$this->checkError($this->db);

		// In the case of MySQL UTF8, there is an additional command to run!
		if ($this->dsn["phptype"]=='mysql') {
			$charset = strtolower($dsn['charset']);
			if ($charset == 'utf8' || $charset == 'utf-8')
			$this->query("SET NAMES 'utf8'");
		}*/

	}
	
	/**
	 * Performs the connection to the the database.
	 *
	 */
	public function connect() {
		$this->dbh = new PDO($this->getDsn(), $this->getUserName(), $this->getPassword(), $this->getOptions());
	}
	
	
	/**
	 * Runs the query against the database.
	 *
	 * @param string $query The query to run
	 * @return int the number of line affected
	 */
	public function exec($query) {
		if ($this->dbh == null) {
			$this->connect();
		}
		
		$res = $this->dbh->exec($query);
		return $res;
	}

	/**
	 * Runs the query and returns all lines in an associative table.
	 *
	 * @param string $query
	 * @param int $mode
	 * @return unknown
	 */
	public function getAll($query, $mode = PDO::FETCH_ASSOC) {
		if ($this->dbh == null) {
			$this->connect();
		}
		
		$stmt = $this->dbh->query($query);
		$stmt->setFetchMode($mode);
		$arrValues = $stmt->fetchAll();
		$stmt->closeCursor();
		
		return $arrValues;
	}

	/**
	 * Runs the query and returns the one and only value returned by this query.
	 *
	 * @param string $query
	 * @return mixed
	 */
	public function getOne($query) {
		if ($this->dbh == null) {
			$this->connect();
		}
		
		$stmt = $this->dbh->query($query);
		$value = $stmt->fetchColumn();
		$stmt->closeCursor();
		
		return $value;
	}

	/**
	 * Protects the string (by adding \ in front of '.
	 * TODO: Migrate to use prepared statements!!
	 * 
	 * @param string $in
	 * @return string
	 */
	public function quoteSmart($in) {
		return $this->dbh->quote($in);
	}

	/**
	 * Returns the next Id from the sequence.
	 *
	 * @param string $seq_name The name of the sequence
	 * @param boolean $onDemand If true, if the sequence does not exist, it will be created.
	 * @return unknown The next value of the sequence
	 */
	public function nextId($seq_name, $onDemand = true) {
		$id = $this->db->nextId($seq_name, $onDemand);
		$this->checkError($id, 'Error while querying peardb sequence name '.$seq_name.'\n'.
			"Possible symptom: the sequence is named $seq_name and your database user (".$this->getUserName().") does not have the rights to create it.");
		return $id;
	}

	/**
	 * Sets the sequence to the passed value.
	 *
	 * @param string $seq_name
	 * @param unknown_type $id
	 */
	public function setSequenceId($table_name, $id) {
		$seq_name = sprintf($this->options['seqname_format'], $table_name);

		if ($this->dsn["phptype"]=='pgsql') {
			$this->exec("SELECT setval('$seq_name', '$id')");
		} elseif ($this->dsn["phptype"]=='mysql') {
			$this->exec("UPDATE $seq_name SET ID='$id'");
		} else {
			throw new TDBM_Exception('Unable to set the sequence value for database type '.$this->dsn['phptype'].'<br />\nCurrently, only MySQL 5+ and PostGreSQL 7+ are supported.');
		}
	}

	/**
	 * Returns Root Sequence Table for $table_name
	 * i.e. : if "man" table inherits "human" table , returns "human" for Root Sequence Table
	 * !! Warning !! Child table must share Mother table's primary key
	 * @param unknown_type $table_name
	 */
	abstract protected function findRootSequenceTable($table_name);

	/**
	 * Returns parent table according to child parent's primary key's contraint
	 * !! warning !! : we assume no DBMS can add a constraint from a primary key to a targeted column that is not a primary key.
	 * TODO : à compléter
	 * @param unknown_type $table_name
	 */
	private function getParentTableByPrimaryForeignKey($table_name){
		$primary_key = $this->getPrimaryKeyWithCache($table_name);
		$parent_table = null;
		// Primary Keys made of several columns are not handled
		if (count($primary_key)>1) {
			throw new TDBM_Exception('Unable to handle multi-column primary keys. <br />\n
			Can\'t find Root Sequence Table for table '.$table_name );
		}elseif (count($primary_key)==0){
			throw new TDBM_Exception('No primary key for table '.$table_name );
		}
		$primary_key = $primary_key[0];
		$constraint_array = $this->getConstraintsFromTableWithCache($table_name);
		foreach ($constraint_array as $constraint){
			if ($constraint['col1']==$primary_key) {
				$parent_table = $constraint['table_2'];
				break;
			}
		}
		return $parent_table;
	}

	/**
	 * Returns Root Sequence Table for $table_name
	 * i.e. : if "man" table inherits "human" table , returns "human" for Root Sequence Table
	 * !! Warning !! Child table must share Mother table's primary key
	 * @param unknown_type $table_name
	 */
	public function findRootSequenceTableWithCache($table_name){
		if (!isset($_SESSION['__TDBM_CACHE__']['inherits'][$table_name]))
		{
			$_SESSION['__TDBM_CACHE__']['inherits'][$table_name] = $this->findRootSequenceTable($table_name);
		}
		return $_SESSION['__TDBM_CACHE__']['inherits'][$table_name];
	}

	/**
	 * Returns the parent table (if the table inherits from another table).
	 * For DB systems that do not support inheritence, returns the table name.
	 *
	 * @param string $table_name
	 * @return string
	 */
	abstract public function getParentTable($table_name);
	
	/**
	 * Returns an array of columns that are declared to be primary keys for this table.
	 *
	 * @param string $table_name the table name
	 * @return array an array of the primary key columns of the table
	 */
	abstract protected function getPrimaryKey($table_name);

	public function getPrimaryKeyWithCache($table_name) {
		if (!isset($_SESSION['__TDBM_CACHE__']['pk'][$table_name]))
		{
			$_SESSION['__TDBM_CACHE__']['pk'][$table_name] = $this->getPrimaryKey($table_name);
		}
		return $_SESSION['__TDBM_CACHE__']['pk'][$table_name];
	}
	
	/**
	 * Returns the table columns.
	 *
	 * @param string $tableName
	 * @return array<array> An array representing the columns for the specified table.
	 */
	public function getTableInfo($tableName) {
		
		$str = "SELECT * FROM information_schema.COLUMNS WHERE table_name = ".$this->quoteSmart($tableName).";";

		$res = $this->getAll($str);
		
		return $res;
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
	abstract public function getConstraintsOnTable($table_name,$column_name=false);
	
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
	abstract public function getConstraintsFromTable($table_name,$column_name=false);

	/**
	 * Returns a table of rows with structure identic to getConstraintsFromTable
	 * Provides a mechanism of caching above that.
	 * Caching is kept in session, if sessions are active.
	 *
	 * @param unknown_type $foreign_table
	 * @param unknown_type $fail_if_empty if true, throws an Exception if no constraint has been found.
	 * @return unknown
	 */
	public function getConstraintsFromTableWithCache($table) {
		/*if (!isset(TDBM_Object::$constraints_one_star[$table]))
		 {
			TDBM_Object::$constraints_one_star[$table] = $this->db_connection->getConstraintsFromTable($table);
			}
			return TDBM_Object::$constraints_one_star[$table];*/

		if (!isset($_SESSION['__TDBM_CACHE__']['constraints_one_star'][$table]))
		{
			$_SESSION['__TDBM_CACHE__']['constraints_one_star'][$table] = $this->getConstraintsFromTable($table);
		}
		return $_SESSION['__TDBM_CACHE__']['constraints_one_star'][$table];

	}

	public function getConstraintsOnTableWithCache($table) {
		/*if (!isset(TDBM_Object::$constraints_one_star[$table]))
		 {
			TDBM_Object::$constraints_one_star[$table] = $this->db_connection->getConstraintsFromTable($table);
			}
			return TDBM_Object::$constraints_one_star[$table];*/

		if (!isset($_SESSION['__TDBM_CACHE__']['constraints_star_one'][$table]))
		{
			$_SESSION['__TDBM_CACHE__']['constraints_star_one'][$table] = $this->getConstraintsOnTable($table);
		}
		return $_SESSION['__TDBM_CACHE__']['constraints_star_one'][$table];

	}

	/**
	 * Returns a table of rows with structure:
	 * ("constraining_column" => XXX, "constrained_column" => XXX)
	 *
	 * @param unknown_type $constraining_table The constraining table
	 * @param unknown_type $constrained_table The constrained table
	 * @return unknown
	 */
	/*	function getConstraintsBetweenTable($constraining_table,$constrained_table) {
		if ($this->dsn["phptype"]=='pgsql')
		{
		$sql = "SELECT c1.attname as constraining_column, c2.attname as constrained_column FROM
		pg_attribute c2 JOIN pg_class t2 JOIN
		(pg_constraint con JOIN
		(pg_class t1 JOIN pg_attribute c1 ON t1.oid = c1.attrelid)
		ON con.conrelid = t1.oid AND con.conkey[1]=c1.attnum)
		ON t2.oid = con.confrelid ON c2.attrelid = t2.oid AND con.confkey[1]=c2.attnum
		WHERE t2.relname='$constrained_table' AND t1.relname='$constraining_table'";
			
		$result = $this->db->getAll($sql,null,DB_FETCHMODE_ASSOC);
		$this->checkError($result, $sql);
		return $result;
		}
		throw new TDBM_Exception('Unable to retrieve Constraints for database type '.$this->dsn['phptype']);
		}

		function findPivotTable($table1,$table2) {
		if ($this->dsn["phptype"]=='pgsql')
		{
		$sql = "SELECT col1.attname AS col1, colpivot1.attname AS colpivot1, pivottable.relname AS pivottable, colpivot2.attname AS colpivot2, col2.attname AS col2 FROM
		(((((((pg_class pivottable JOIN pg_constraint con12 ON pivottable.oid = con12.conrelid)
		JOIN pg_constraint con23 ON pivottable.oid = con23.conrelid)
		JOIN pg_class t1 ON t1.oid = con12.confrelid)
		JOIN pg_class t2 ON t2.oid = con23.confrelid)
		JOIN pg_attribute col1 ON t1.oid = col1.attrelid AND con12.confkey[1]=col1.attnum)
		JOIN pg_attribute col2 ON t2.oid = col2.attrelid AND con23.confkey[1]=col2.attnum)
		JOIN pg_attribute colpivot1 ON pivottable.oid = colpivot1.attrelid AND con12.conkey[1]=colpivot1.attnum)
		JOIN pg_attribute colpivot2 ON pivottable.oid = colpivot2.attrelid AND con23.conkey[1]=colpivot2.attnum
		WHERE t2.relname='$table1' AND t1.relname='$table2'";
			
		$result = $this->db->getAll($sql,null,DB_FETCHMODE_ASSOC);
		$this->checkError($result, $sql);
		return $result;
		}
		throw new TDBM_Exception('Unable to retrieve Constraints for database type '.$this->dsn['phptype']);
		}*/

	public function getInsertId($table_name,$pkey_field_name) {
		$sql="SELECT max($pkey_field_name) AS id FROM $table_name";
		//echo $sql;
		$result=$this->getOne($sql);
		
		return $result;
	}

	function affectedRows() {
		return $this->db->affectedRows();
	}

	function checkError($TDBM_Object, $additional_error_message=null) {
		if (PEAR::isError($TDBM_Object)) {
			$message = 'Standard Message: ' . $TDBM_Object->getMessage() . "\n".
				'Standard Code: ' . $TDBM_Object->getCode() . "\n".
				'DBMS/User Message: ' . $TDBM_Object->getUserInfo() . "\n".
				'DBMS/Debug Message: ' . $TDBM_Object->getDebugInfo() . "\n";
			if ($additional_error_message != null)
			{
				$message .= 'Additional error message: '.$additional_error_message;
			}
			throw new TDBM_Exception($message);
		}
	}

	/**
	 * Returns the columns information from the cache, or from the DB if not in cache.
	 *
	 * @param string $table
	 * @return array
	 */
	public function getTableInfoWithCache($table) {

		if (!isset($_SESSION['__TDBM_CACHE__']['table_info'][$table]))
		{
			// TODO migrate this to use getTableInfo
			$data = $this->db->tableInfo($table);
			
			$columns_data = array();
			
			// Ok, let's take the data from the table and reorganize that data in an associative array where the column name
			// is the first parameter (more efficient for searching column data!)
			foreach ($data as $column) {
				$columns_data[$column['name']] = $column;
			}

			if (PEAR::isError($data)) {
				throw new TDBM_Exception("Error while retrieving information for table ".$table);
			}
			
			$_SESSION['__TDBM_CACHE__']['table_info'][$table] = $columns_data;
		}
		return $_SESSION['__TDBM_CACHE__']['table_info'][$table];

	}
	


	/**
	 * Returns true if the table exists.
	 * Otherwise, tries to find a close match and returns a table of the closest matches.
	 *
	 * Returns null in case of error.
	 * This function is used in case an exception is thrown to try to help the user find which table he wants.
	 *
	 * @param string $table_name name of the table to find
	 */
	function checkTableExist($table_name) {
		// Once you have a valid DB object named $db...
		$data = $this->db->getListOf('tables');

		if (PEAR::isError($data)) {
			return null;
		}

		foreach ($data as $current_table) {
			if ($current_table==$table_name)
			return true;
		}

		// If we are here, table was not found

		// Let's compute the lenvenstein distance and keep the smallest one in $smallest.
		$smallest = 99999999;
		$distance_table = array();

		foreach ($data as $current_table) {
			$distance = levenshtein($table_name, $current_table);
			$distance_table[$current_table]=$distance;
			if ($distance<$smallest)
			$smallest = $distance;
		}

		$result_array = array();
		foreach ($distance_table as $table => $distance) {
			if ($smallest == $distance)
			$result_array[] = $table;
		}

		return $result_array;
	}

	/**
	 * Returns true if the column in the given table exists.
	 * Otherwise, tries to find a close match and returns an array of the closest matches.
	 *
	 * Returns null in case of error.
	 * This function is used in case an exception is thrown to try to help the user find which column he wants.
	 *
	 * @param unknown_type $table_name
	 * @param unknown_type $column_name
	 * @return unknown
	 */
	function checkColumnExist($table_name, $column_name) {
		// Once you have a valid DB object named $db...
		try {
			$data = $this->getTableInfoWithCache($table_name);
		} catch (TDBM_Exception $ex) {
			// If the table does not exist, let's return null.
			return null;
		}
		/*
		$data = $this->db->tableInfo($table_name);
		

		if (PEAR::isError($data)) {
			return null;
		}*/

		foreach ($data as $current_column) {
			if ($this->toStandardcaseColumn($current_column['name'])==$column_name)
				return true;
		}

		// If we are here, table was not found

		// Let's compute the lenvenstein distance and keep the smallest one in $smallest.
		$smallest = 99999999;
		$distance_column = array();

		foreach ($data as $current_column) {
			$distance = levenshtein($column_name, $current_column['name']);
			$distance_column[$current_column['name']]=$distance;
			if ($distance<$smallest)
			$smallest = $distance;
		}

		$result_array = array();
		foreach ($distance_column as $table => $distance) {
			if ($smallest == $distance)
			$result_array[] = $table;
		}

		return $result_array;
	}
	
	/**
	 * Returns, depending on the database system used and file system used the string passed
	 * in parameter in lowercase or in the same case.
	 * For instance, with a PgSQL database, you will always get a lowercase string.
	 * On MySQL, it will depend the system used. By default, on Windows, it should return a lowercase string
	 * while on Linux, it will return the same string.
	 * The database setting is retrieved only once and stored in session to avoid unnecessary database calls.
	 *
	 * TODO: change the session mecanism so we can use 2 different databases. Right now, they should have the same
	 * case sensitivity settings, which is not good.
	 *
	 */
	function toStandardcase($string) {
		$case_sensitive = $_SESSION['__TDBM_CACHE__']['case_sensitive'];
		if ($case_sensitive === null) {

			if ($this->dsn["phptype"]=='pgsql') {
				$_SESSION['__TDBM_CACHE__']['case_sensitive'] = false;
			} else if ($this->dsn["phptype"]=='mysql') {
				$case_sensitive_result = $this->getAll("SHOW VARIABLES WHERE Variable_name = 'lower_case_table_names'");

				if (count($case_sensitive_result)==0) {
					throw new TDBM_Exception('Unable to retrieve case sensitivity for your MySQL database.<br />\nPlease note only MySQL 5+ and PostGreSQL 7+ are supported.');
				}
				if ($case_sensitive_result[0]['Value'] == 1 || $case_sensitive_result[0]['Value'] == 2) {
					$_SESSION['__TDBM_CACHE__']['case_sensitive'] = false;
				} else {
					$_SESSION['__TDBM_CACHE__']['case_sensitive'] = true;
				}
			} else {
				throw new TDBM_Exception('Unable to retrieve case sensitivity for database type '.$this->dsn['phptype'].'<br />\nCurrently, only MySQL 5+ and PostGreSQL 7+ are supported.');
			}

			$case_sensitive = $_SESSION['__TDBM_CACHE__']['case_sensitive'];
		}

		if ($case_sensitive) {
			return $string;
		} else {
			return strtolower($string);
		}
	}

	/**
	 * Returns, depending on the database system used and file system used the string passed 
	 * in parameter in lowercase or in the same case.
	 * This lowercasing mecanism is used for columns.
	 * If a column name is case insensitive, this will return the column name in lowercase.
	 * 
	 * Note: in the current implementation, both MySQL and PostgreSQL are case insensitive.
	 * PostgreSQL is case sensitive if a column is quoted, BUT TDBM does not quote columns.
	 * 
	 */
	function toStandardcaseColumn($string) {
		return strtolower($string);
	}

	/**
	 * Turns auto-commit on or off.
	 *
	 * @param bool $onOff
	 */
	public function autoCommit($onOff = true) {
		TDBM_Object::completeSave();
		$result = $this->db->autoCommit($onOff);
		$this->checkError($result);
		$this->autoCommit = $onOff;
	}
	
	/**
	 * Returns true if the DB is in autocommit mode, false if commit or rollback is manual.
	 *
	 * @return boolean
	 */
	public function isAutoCommit() {
		return $this->autoCommit;
	}

	/**
	 * Commits the current transaction.
	 *
	 */
	public function commit() {
		TDBM_Object::completeSave();
		$result = $this->db->commit();
		$this->checkError($result);
	}

	/**
	 * Rolls back the current transaction.
	 *
	 */
	public function rollback() {
		// TODO: since we are rolling back, we should remove anything in TDBM_Object::$new_objects
		// instead of inserting in order to roll back.
		TDBM_Object::completeSave();
		$result = $this->db->rollback();
		$this->checkError($result);
	}
	
	/**
	 * Should we commit anything when the process ends?
	 * This is useful only if autoCommit is set to no.
	 * Default is true.
	 * 
	 * @param unknown_type $commit
	 */
	public function setCommitOnQuit($commit) {
		$this->commitOnQuit = $commit;
	}
	
	/**
	 * True if we commit all pending requests when the process ends.
	 * This is useful only if autoCommit is set to no.
	 *
	 * @return boolean
	 */
	public function isCommitOnQuit() {
		return $this->commitOnQuit;
	}

	/**
	 * Returns the column type of the column $column from table $table
	 * If the column does not exist, returns null.
	 * If the table does not exist, throws a TDBM_Exception
	 *
	 * @param string $table
	 * @param string $column
	 */
	public function getColumnType($table, $column) {
		$table_info = $this->getTableInfoWithCache($table);
		if (!isset($table_info[$column]))
			return null;
		return $table_info[$column]['type'];
	}
	
	/**
	 * Checks whether the $value passed is compatible with the SQL $type passed.
	 * For instance checkType(4, 'INTEGER') will return true.
	 * checkType('toto', 'INTEGER') will return false.
	 *
	 * @param unknown_type $value
	 * @param string $type
	 * @return boolean
	 */
	public function checkType($value, $type) {
		switch ($type) {
			case "int2":
			case "int4":
			case "int8":
				if ($value != null && !is_numeric($value))
					return false;
				break;
			case "float4":
			case "float8":
				if ($value != null && !is_numeric($value))
					return false;
				break;
				
		}
		// TODO: MySQL Types, date types.
		return true;
	}
	
	/**
	 * Creates a new table in the database.
	 *
	 * @param string $tableName The table name
	 * @param array<Db_Column> $columnsList
	 * @param boolean $dropIfExist whether the table should be dropped or not if it exists.
	 */
	abstract public function createTable($tableName, $columnsList, $dropIfExist);
	
	/**
	 * Creates a new index in the database.
	 *
	 * @param string $tableName
	 * @param array<string> $columnsList
	 * @param boolean $isUnique whether the index is unique or not.
	 * @param string $indexName The index name, generated if not specified.
	 */
	abstract public function createIndex($tableName, $columnsList, $isUnique, $indexName=null);
}


?>