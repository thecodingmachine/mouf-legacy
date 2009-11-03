<?php
/*
 Copyright (C) 2006-2009 David Négrier - THE CODING MACHINE

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



class TDBM_Object {

	/**
	 * The main db connection
	 *
	 * @var DB_ConnectionInterface
	 */
	static public $main_db;
	
	static private $table_descs;

	/**
	 * Cache of table of primary keys.
	 * Primary keys are stored by tables, as an array of column.
	 * For instance $primary_key['my_table'][0] will return the first column of the primary key of table 'my_table'.
	 *
	 * @var array
	 */
	static private $primary_keys;

	/**
	 * Table of objects that are cached in memory.
	 * Access is done by table name and then by primary key.
	 * If the primary key is split on several columns, access is done by an array of columns, serialized.
	 *
	 * eg: $objects['my_table'][12]
	 */
	static private $objects;

	/// Table of new objects not yet inserted in database.
	static private $new_objects;

	/// Table of constraints that that table applies on another table n the form [this table][this column]=XXX
	//static private $external_constraint;

	/// Table of foreign constraints that are applied on that table in the form [this table(constrained table)][foreign table (constraining table)]=array(['constraining_column']=>$column_name, ['constrained_table']=>$column_name)
	static private $foreign_constraints;

	/// Table containing the name of pivot table between 2 tables in the form [table1][table2]=pivot_table
	static private $pivot_constraints;

	/// The timestamp of the script startup. Useful to stop execution before time limit is reached and display useful error message.
	static public $script_start_up_time;

	/// True if the program is exiting (we are in the "exit" statement). False otherwise.
	static private $is_program_exiting = false;

	/*
	 * Table of foreign constraints in the 1* schema. Very similar to foreign_constraint except in its use:
	 * With $constraints_one_star, we provide caching on a table basis: All the rtables and constraints
	 * associated to this table are retrieved in one go by getConstraintsFromTableWithCache function.
	 * CANCELED: we put that in session instead
	 */
	//static private $constraints_one_star;

	/**
	 * The name of the table the object if issued from
	 *
	 * @var string
	 */
	private $db_table_name;

	/**
	 * The array of columns returned from database.
	 *
	 * @var array
	 */
	private $db_row;

	/**
	 * One of "new", "not loaded", "loaded", "deleted".
	 * $DBM_Object_state = "new" when a new object is created with DBMObject:getNewObject.
	 * $DBM_Object_state = "not loaded" when the object has been retrieved with getObject but when no data has been accessed in it yet.
	 * $DBM_Object_state = "loaded" when the object is cached in memory.
	 *
	 * @var string
	 */
	private $DBM_Object_state;

	/**
	 * True if the object has been modified and must be saved.
	 *
	 * @var boolean
	 */
	private $db_modified_state;

	/**
	 * True if an error has occured while saving. The user will have to call save() explicitly or to modify one of its members to save it again.
	 *
	 * @var boolean
	 */
	private $db_onerror;


	private $DBM_Object_id;

	/**
	 * dependency between columns of objects and linked objects in the form: $this->dependency[$row] = $object
	 *
	 * Used in setonestar... TODO
	 */
	private $db_dependency;

	private $db_connection;
	
	/**
	 * True to automatically save the object.
	 * If false, the user must explicitly call the save() method to save the object. 
	 *
	 * @var boolean
	 */
	private $db_autosave;
	
	/**
	 * The default autosave mode for the objects
	 * True to automatically save the object.
	 * If false, the user must explicitly call the save() method to save the object. 
	 * 
	 * @var boolean
	 */
	private static $autosave_default = true;

	/**
	 * Sets up the default connection to the database.
	 * The parameters of TDBM_Object::connect are similar to the parameters used by PEAR DB (since TDBM_Object relies on PEAR DB).
	 *
	 * For instance:
	 * TDBM_Object::connect(array(
	 *    'phptype'  => 'pgsql',
	 *    'username' => 'my_user',
	 *    'password' => 'my_password',
	 *    'hostspec' => 'ip_of_my_database_server',
	 *    'database' => 'name_of_my_base'
	 * ));
	 *
	 * where phptype is the type of database supported (currently can be only 'pgsql' for PostGreSQL)
	 *       username is the name of your database user
	 *       password is the password of your rdatabase user
	 *       hostspec is the IP of your database server (very likely, it will be 'localhost' for you)
	 *       database is the name of your database
	 *
	 * @param array $dsn The DSN to access the database. See Pear DB.
	 * @param array $options The connect database options. Optionnal. See Pear DB.
	 */
	static public function connect(DB_ConnectionInterface $connection) {
		TDBM_Object::$main_db = $connection;
	}

	/**
	 * Returns the TDBM_Object associated to the row "$id" of the table "$table_name".
	 *
	 * For instance, if there is a table 'users', with a primary key on column 'user_id' and a column 'user_name', then
	 * 			$user = TDBM_Object::getObject('users',1);
	 * 			echo $user->name;
	 * will return the name of the user whose user_id is one.
	 *
	 * If a table has a primary key over several columns, you should pass to $id an array containing the the value of the various columns.
	 * For instance:
	 * 			$group = TDBM_Object::getObject('groups',array(1,2));
	 *
	 * Note that TDBM_Object performs caching for you. If you get twice the same object, the reference of the object you will get
	 * will be the same.
	 *
	 * For instance:
	 * 			$user1 = TDBM_Object::getObject('users',1);
	 * 			$user2 = TDBM_Object::getObject('users',1);
	 * 			$user1->name = 'John Doe';
	 * 			echo $user2->name;
	 * will return 'John Doe'.
	 *
	 * @param string $table_name The name of the table we retrieve an object from.
	 * @param unknown_type $id The id of the object (the value of the primary key).
	 * @return TDBM_Object
	 */
	static public function getObject($table_name, $id) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		$table_name = TDBM_Object::$main_db->toStandardcase($table_name);

		// If the ID is null, let's throw an exception
		if ($id === null) {
			throw new TDBM_Exception("The ID you passed to TDBM_Object::getObject is null for the object of type '$table_name'. Objects primary keys cannot be null.");
		}

		// If the primary key is split over many columns, the IDs are passed in an array. Let's serialize this array to store it.
		if (is_array($id)) {
			$id = serialize($id);
		}

		if (isset(TDBM_Object::$objects[$table_name][$id]))
		return TDBM_Object::$objects[$table_name][$id];

		TDBM_Object::$objects[$table_name][$id] = new TDBM_Object(TDBM_Object::$main_db, $table_name, $id);
		return TDBM_Object::$objects[$table_name][$id];
	}

	/**
	 * Creates a new object that will be stored in table "table_name".
	 * If $auto_assign_id is true, the primary key of the object will be automatically be filled.
	 * Otherwise, the database system or the user will have to fill it itself (for exemple with
	 * AUTOINCREMENT in MySQL or with a sequence in POSTGRESQL).
	 *
	 * Please note that $auto_assign_id does not work on tables that have primary keys on multiple
	 * columns.
	 *
	 * @param string $table_name
	 * @param boolean $auto_assign_id
	 * @return TDBM_Object
	 */
	static public function getNewObject($table_name, $auto_assign_id=true) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getNewObject(): No connection has been established on the database!");
		}
		$table_name = TDBM_Object::$main_db->toStandardcase($table_name);
		
		// Ok, let's verify that the table does exist:
		try {
			$data = TDBM_Object::$main_db->getTableInfoWithCache($table_name);
		} catch (TDBM_Exception $exception) {
			$probable_table_name = TDBM_Object::$main_db->checkTableExist($table_name);
			if ($probable_table_name == null)
				throw new TDBM_Exception("Error while calling TDBM_Object::getNewObject(): The table named '$table_name' does not exist.");
			else
				throw new TDBM_Exception("Error while calling TDBM_Object::getNewObject(): The table named '$table_name' does not exist. Maybe you meant the table '$probable_table_name'.");
		}

		$object = new TDBM_Object(TDBM_Object::$main_db, $table_name);

		if ($auto_assign_id) {
			$pk_table = $object->getPrimaryKey();
			if (count($pk_table)==1)
			{
				$root_table = TDBM_Object::$main_db->findRootSequenceTableWithCache($table_name);
				$id = TDBM_Object::$main_db->nextId($root_table);
				// If $id == 1, it is likely that the sequence was just created.
				// However, there might be already some data in the database. We will check the biggest ID in the table.
				if ($id == 1) {
					$sql = "SELECT MAX(".$pk_table[0].") AS maxkey FROM ".$root_table;
					$res = TDBM_Object::$main_db->getAll($sql);
					// NOTE: this will work only if the ID is an integer!
					$newid = $res[0]['maxkey'] + 1;
					if ($newid>$id) {
						$id = $newid;
					}
					TDBM_Object::$main_db->setSequenceId($root_table, $id);
				}

				$object->DBM_Object_id = $id;

				$object->db_row[$pk_table[0]] = $object->DBM_Object_id;
			}
		}

		TDBM_Object::$new_objects[] = $object;

		return $object;
	}

	/**
	 * Constructor.
	 * Used with id!=false when we want to retrieve an existing object
	 * and id==false if we want a new object
	 *
	 * @param string $table_name
	 * @param unknown_type $id
	 */
	private function __construct($db_connection, $table_name, $id=false) {
		$this->db_connection = $db_connection;
		$this->db_table_name = $table_name;
		$this->DBM_Object_id = $id;
		$this->db_modified_state = false;
		$this->db_onerror = false;
		if ($id !== false)
			$this->DBM_Object_state = "not loaded";
		else
			$this->DBM_Object_state = "new";
		
		$this->db_autosave = self::$autosave_default;
	}

	/**
	 * Returns true if the object will save automatically, false if an explicit call to save() is required.
	 *
	 * @return boolean
	 */
	public function getAutoSaveMode() {
		return $this->db_autosave;
	}
	
	/**
	 * Sets the autosave mode:
	 * true if the object will save automatically,
	 * false if an explicit call to save() is required.
	 *
	 * @param unknown_type $autoSave
	 * @return boolean
	 */
	public function setAutoSaveMode($autoSave) {
		$this->db_autosave = $autoSave;
	}
	
	/**
	 * Returns true if the objects will save automatically by default,
	 * false if an explicit call to save() is required.
	 * 
	 * The behaviour can be overloaded by setAutoSaveMode on each object.
	 *
	 * @return boolean
	 */
	public static function getDefaultAutoSaveMode() {
		return self::$autosave_default;
	}
	
	/**
	 * Sets the autosave mode:
	 * true if the object will save automatically,
	 * false if an explicit call to save() is required.
	 *
	 * @param unknown_type $autoSave
	 * @return boolean
	 */
	public static function setDefaultAutoSaveMode($autoSave) {
		self::$autosave_default = $autoSave;
	}
	
	/**
	 * Loads the db_row property of the object from the $row array.
	 * Any row having a key starting with 'tdbm_reserved_col_' is ignored.
	 *
	 * @param array $row
	 */
	private function loadFromRow($row) {
		foreach ($row as $key=>$value) {
			if (strpos($key, 'tdbm_reserved_col_')!==0) {
				$this->db_row[$key]=$value;
			}
		}

		$this->DBM_Object_state = "loaded";
	}

	/**
	 * Returns an array of the columns composing the primary key for that object.
	 * This methods caches the primary keys so that if it is called twice, the second call will
	 * not make any query to the database.
	 *
	 * TODO: cache this in session
	 *
	 * @return array
	 */
	private function getPrimaryKey() {
		return TDBM_Object::getPrimaryKeyStatic($this->db_table_name, $this->db_connection);
	}

	private function getPrimaryKeyStatic($table, Mouf_DBConnection $conn) {
		if (!isset(TDBM_Object::$primary_keys[$table]))
		{
			$arr = array();
			foreach ($conn->getPrimaryKey($table) as $col) {
				$arr[] = $col->name;
			}
			// The primary_keys contains only the column's name, not the DB_Column object.
			TDBM_Object::$primary_keys[$table] = $arr;
			if (empty(TDBM_Object::$primary_keys[$table]))
			{
				// Unable to find primary key.... this is an error
				// Let's try to be precise in error reporting. Let's try to find the table.
				$tables = $conn->checkTableExist($table);
				if ($tables === true)
				throw new TDBM_Exception("Could not find table primary key for table '$table'. Please define a primary key for this table.");
				elseif ($tables !== null) {
					if (count($tables)==1)
					$str = "Could not find table '$table'. Maybe you meant this table: '".$tables[0]."'";
					else
					$str = "Could not find table '$table'. Maybe you meant one of those tables: '".implode("', '",$tables)."'";
					throw new TDBM_Exception($str);
				}
			}
		}
		return TDBM_Object::$primary_keys[$table];
	}

	/*private function getColumnConstrainedBy($column) {
		if (!isset(TDBM_Object::$external_constraint[$this->db_table_name][$column]))
		{
		TDBM_Object::$external_constraint[$this->db_table_name][$column] = db_getcolumnconstrainedby($this->db_table_name, $column);
		if (TDBM_Object::$external_constraint[$this->db_table_name][$column] == false)
		{
		throw new Exception("Could not find any external constraint on column $column.");
		}
		}
		return TDBM_Object::$external_constraint[$this->db_table_name][$column];
		}*/

	/*private function getConstraintsOnTable($foreign_table, $fail_if_empty=true) {
		if (!isset(TDBM_Object::$foreign_constraints[$this->db_table_name][$foreign_table]))
		{
		////////// TODO A VERIFIER.....
		TDBM_Object::$foreign_constraints[$this->db_table_name][$foreign_table] = $this->db_connection->getConstraintsBetweenTable($foreign_table, $this->db_table_name);
		if (TDBM_Object::$foreign_constraints[$this->db_table_name][$foreign_table] == false)
		{
		if ($fail_if_empty)
		throw new TDBM_Exception("Could not find any constraint from table $foreign_table on table $this->db_table_name.");
		else
		return false;
		}
		}
		return TDBM_Object::$foreign_constraints[$this->db_table_name][$foreign_table];
		}*/

	/**
	 * Returns a table of rows with structure:
	 * ("constraining_column" => XXX, "constrained_column" => XXX)
	 *
	 * @param unknown_type $foreign_table
	 * @param unknown_type $fail_if_empty if true, throws an Exception if no constraint has been found.
	 * @return unknown
	 */
	/*private function getConstraintsFromTable($foreign_table, $fail_if_empty=true) {
		if (!isset(TDBM_Object::$foreign_constraints[$foreign_table][$this->db_table_name]))
		{
		TDBM_Object::$foreign_constraints[$foreign_table][$this->db_table_name]= $this->db_connection->getConstraintsBetweenTable($this->db_table_name, $foreign_table);
		if (TDBM_Object::$foreign_constraints[$foreign_table][$this->db_table_name] == false)
		{
		if ($fail_if_empty)
		throw new TDBM_Exception("Could not find any constraint from table $this->db_table_name on table $foreign_table.");
		else
		return false;
		}
		}
		return TDBM_Object::$foreign_constraints[$foreign_table][$this->db_table_name];
		}

		private function getPivotTables($table_name) {
		if (!isset(TDBM_Object::$pivot_constraints[$this->db_table_name][$table_name]))
		{
		$pivot_tables_data = $this->db_connection->findPivotTable($table_name, $this->db_table_name);
			
		if ($pivot_tables_data == false)
		{
		throw new TDBM_Exception("Could not find any pivot table between table $table_name and table $this->db_table_name.");
		}
			
		//unset (TDBM_Object::$foreign_constraints[$this->db_table_name][$pivot_table]);
		//unset (TDBM_Object::$foreign_constraints[$table_name][$pivot_table]);
			
		$i=0;
		foreach ($pivot_tables_data as $pivot_data) {
		$pivot_table = $pivot_data['pivottable'];

			
		TDBM_Object::$pivot_constraints[$this->db_table_name][$table_name][] = $pivot_table;
		TDBM_Object::$pivot_constraints[$table_name][$this->db_table_name][] = $pivot_table;

		$constraint1['constraining_column'] = $pivot_data['colpivot1'];
		$constraint1['constrained_column'] = $pivot_data['col1'];
		TDBM_Object::$foreign_constraints[$this->db_table_name][$pivot_table][$i] = $constraint1;
		$constraint2['constraining_column'] = $pivot_data['colpivot2'];
		$constraint2['constrained_column'] = $pivot_data['col2'];
		TDBM_Object::$foreign_constraints[$table_name][$pivot_table][$i] = $constraint2;
		$i++;
		}
			
		}
		return TDBM_Object::$pivot_constraints[$this->db_table_name][$table_name];
		}*/

	/**
	 * If the object is in state 'not loaded', this method performs a query in database to load the object.
	 *
	 * A TDBM_Exception is thrown is no object can be retrieved (for instance, if the primary key specified
	 * cannot be found).
	 */
	private function dbLoadIfNotLoaded() {
		if ($this->DBM_Object_state == "not loaded")
		{
			// Let's first get the primary keys
			$pk_table = $this->getPrimaryKey();
			// Now for the object_id
			$object_id = $this->DBM_Object_id;
			// If there is only one primary key:
			if (count($pk_table)==1) {
				$sql_where = $pk_table[0]."=".$this->db_connection->quoteSmart($this->DBM_Object_id);
			} else {
				$ids = unserialize($object_id);
				$i=0;
				$sql_where_array = array();
				foreach ($pk_table as $pk) {
					$sql_where_array[] = $pk."=".$this->db_connection->quoteSmart($ids[$i]);
					$i++;
				}
				$sql_where = implode(" AND ",$sql_where_array);
			}

			$sql = "SELECT * FROM ".$this->db_table_name." WHERE ".$sql_where;
			$result = $this->db_connection->query($sql);


			if ($result->rowCount()==0)
			{
				throw new TDBM_Exception("Could not retrieve object from table \"$this->db_table_name\" with ID \"".$this->DBM_Object_id."\".");
			}

			$fullCaseRow = $result->fetchAll(PDO::FETCH_ASSOC);
			$this->db_row = array();
			foreach ($fullCaseRow as $key=>$value)  {
				$this->db_row[$this->db_connection->toStandardCaseColumn($key)]=$value;
			}
			
			//$this->db_row = $result->fetchRow(DB_FETCHMODE_ASSOC);
			$this->DBM_Object_state = "loaded";
		}
	}

	public function __get($var) {
		$this->dbLoadIfNotLoaded();

		// Let's only deal with lower case.
		$var = $this->db_connection->toStandardcaseColumn($var);

		if (!array_key_exists($var, $this->db_row)) {
			// Unable to find column.... this is an error if the object has been retrieved from database.
			// If it's a new object, well, that may not be an error after all!
			// Let's check if the column does exist in the table
			$column_exist = $this->db_connection->checkColumnExist($this->db_table_name, $var);
			// If the column DOES exist, then the object is new, and therefore, we should
			// return null.
			if ($column_exist === true) {
				return null;
			}

			// Let's try to be accurate in error reporting. Let's try to find the column.

			$columns = array_keys($this->db_row);

			// Let's compute the lenvenstein distance and keep the smallest one in $smallest.
			$smallest = 99999999;
			$distance_column = array();

			foreach ($columns as $current_column) {
				$distance = levenshtein($var, $current_column);
				$distance_column[$current_column]=$distance;
				if ($distance<$smallest)
				$smallest = $distance;
			}

			$result_array = array();
			foreach ($distance_column as $column => $distance) {
				if ($smallest == $distance)
				$result_array[] = $column;
			}

			if (count($result_array)==1)
			$str = "Could not find column \"$var\" in table \"$this->db_table_name\". Maybe you meant this column: '".$result_array[0]."'";
			else
			$str = "Could not find column \"$var\" in table \"$this->db_table_name\". Maybe you meant one of those columns: '".implode("', '",$result_array)."'";


			throw new TDBM_Exception($str);
		}
			
		return $this->db_row[$var];
	}

	public function __set($var, $value) {
		$this->dbLoadIfNotLoaded();

		// Let's only deal with lower case.
		$var = $this->db_connection->toStandardcaseColumn($var);

		// Ok, let's start by checking the column type
		$type = $this->db_connection->getColumnType($this->db_table_name, $var);

		// Throws an exception if the type is not ok.
		if (!$this->db_connection->checkType($value, $type)) {
			throw new TDBM_Exception("Error! Invalid value passed for attribute '$var' of table '$this->db_table_name'. Passed '$value', but expecting '$type'");
		}
		
		// TODO: we should be able to set the primary key if the object is new....

		if (isset($this->db_row[$var])) {
			foreach ($this->getPrimaryKey() as $pk) {
				if ($pk == $var) {
					throw new TDBM_Exception("Error! Changing primary key value is forbidden.");
				}
			}
		}

		/*if ($var == $this->getPrimaryKey() && isset($this->db_row[$var]))
			throw new TDBM_Exception("Error! Changing primary key value is forbidden.");*/
		$this->db_row[$var] = $value;
		$this->db_modified_state = true;
		// Unset the error since something has changed (Insert or Update could work this time).
		$this->db_onerror = false;
	}

	/**
	 * Saves the current object by INSERTing or UPDAT(E)ing it in the database.
	 */
	public function save() {
		if (!is_array($this->db_row)) {
			return;
		}

		if ($this->DBM_Object_state == "new") {

			// Let's see if the columns for primary key have been set before inserting.
			// We assume that if one of the value of the PK has been set, the values have not been changed.
			$pk_set = false;
			$pk_array = $this->getPrimaryKey();
			foreach ($pk_array as $pk) {
				if ($this->db_row[$pk]!==null) {
					$pk_set=true;
				}
			}
			// if there are many columns for the PK, and none is set, we have no way to find the object back!
			// let's go on error
			if (count($pk_array)>1 && !$pk_set) {
				$msg = "Error! You did not set the primary keys for the new object of type '$this->db_table_name'. TDBM usually assumes that the primary key is automatically set by the DB engine to the maximum value in the database. However, in this case, the '$this->db_table_name' table has a primary key on multiple columns. TDBM would be unable to find back this record after save. Please specify the primary keys for all new objects of kind '$this->db_table_name'.";

				if (!TDBM_Object::$is_program_exiting)
				throw new TDBM_Exception($msg);
				else
				trigger_error($msg, E_USER_ERROR);
			}


			$sql = 'INSERT INTO '.$this->db_table_name.' ('.implode(',', array_keys($this->db_row)).') VALUES (';

			$first = true;
			foreach ($this->db_row as $key=>$value) {
				if (!$first)
				$sql .= ',';
				$sql .= $this->db_connection->quoteSmart($value);
				$first=false;
			}
			$sql .= ')';

			try {
				$this->db_connection->query($sql);
			} catch (TDBM_Exception $e) {
				$this->db_onerror = true;

				// Strange..... if we do not have the line below, bad inserts are not catched.
				// It seems that destructors are called before the registered shutdown function (PHP >=5.0.5)
				//if (TDBM_Object::$is_program_exiting)
				//	trigger_error("program exiting");
				trigger_error($e->getMessage(), E_USER_ERROR);

				if (!TDBM_Object::$is_program_exiting)
				throw $e;
				else
				{
					trigger_error($e->getMessage(), E_USER_ERROR);
				}
			}

			// Let's remove this object from the $new_objects static table.
			foreach (TDBM_Object::$new_objects as $id=>$object) {
				if ($object == $this)
				{
					unset(TDBM_Object::$new_objects[$id]);
					break;
				}
			}

			// If there is only one column for the primary key, and if it has not been filled, let's find it.
			// We assume this is the biggest ID in the database
			if (count($pk_array)==1 && !$pk_set) {
				$this->DBM_Object_id = $this->db_connection->getInsertId($this->db_table_name,$pk_array[0]);
				$this->db_row[$pk_array[0]] = $this->DBM_Object_id;
			} elseif (count($pk_array)==1 && $pk_set) {
				$this->DBM_Object_id = $this->db_row[$pk_array[0]];
			}

			// Ok, now let's get the primary key
			/*$primary_key = $this->getPrimaryKey();

			if (!isset($this->db_row[$primary_key])) {
			$this->DBM_Object_id = $this->db_connection->getInsertId($this->db_table_name,$primary_key);
			$this->db_row[$primary_key] = $this->DBM_Object_id;
			}*/

			// Maybe some default values have been set.
			// Therefore, we must reload the object if required.
			/*$new_db_row = array();
			foreach ($pk_array as $pk) {
				$new_db_row[$pk] = $this->db_row[$pk];
			}
			var_dump($pk_array);
			var_dump($new_db_row);*/
			
			$this->DBM_Object_state = "not loaded";
			$this->db_modified_state = false;
			$this->db_row = null;
			
			// Let's add this object to the list of objects in cache.
			TDBM_Object::$objects[$this->db_table_name][$this->DBM_Object_id] = $this;
			//$this->db_row = $new_db_row;
		} else if ($this->DBM_Object_state == "loaded" && $this->db_modified_state==true) {
			//$primary_key = $this->getPrimaryKey();
			// Let's first get the primary keys
			$pk_table = $this->getPrimaryKey();
			// Now for the object_id
			$object_id = $this->DBM_Object_id;
			// If there is only one primary key:
			if (count($pk_table)==1) {
				$sql_where = $pk_table[0]."=".$this->db_connection->quoteSmart($this->DBM_Object_id);
			} else {
				$ids = unserialize($object_id);
				$i=0;
				$sql_where_array = array();
				foreach ($pk_table as $pk) {
					$sql_where_array[] = $pk."=".$this->db_connection->quoteSmart($ids[$i]);
					$i++;
				}
				$sql_where = implode(" AND ",$sql_where_array);
			}

			$sql = 'UPDATE '.$this->db_table_name.' SET ';

			$first = true;
			foreach ($this->db_row as $key=>$value) {
				if (!$first)
				$sql .= ',';
				$sql .= "$key = ".$this->db_connection->quoteSmart($value);
				$first=false;
			}
			$sql .= ' WHERE '.$sql_where/*$primary_key."='".$this->db_row[$primary_key]."'"*/;

			try {
				$this->db_connection->query($sql);
			} catch (TDBM_Exception $e) {
				if (!TDBM_Object::$is_program_exiting)
				throw $e;
				else
				trigger_error($e->getMessage(), E_USER_ERROR);
			}

			$this->db_modified_state = false;
		}
	}

	function __destruct() {
		// In a destructor, no exception can be thrown (PHP 5 limitation)
		// So we print the error instead
		try {
			if (!$this->db_onerror && $this->db_autosave)
			{
				$this->save();
			}
		} catch (Exception $e) {
			//echo($e->getMessage());
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}

	/**
	 * This function performs a save() of all the objects that have been modified.
	 * This function is automatically called at the end of your script, so you don't have to call it yourself.
	 *
	 * Note: if you want to catch or display efficiently any error that might happen, you might want to call this
	 * method explicitly and to catch any TDBM_Exception that it might throw like this:
	 *
	 * try {
	 * 		TDBM_Object::completeSave();
	 * } catch (TDBM_Exception e) {
	 * 		// Do something here.
	 * }
	 *
	 */
	static function completeSave() {
		
		if (is_array(TDBM_Object::$new_objects))
		{
			foreach (TDBM_Object::$new_objects as $key=>$object)
			{
				if (!$object->db_onerror && $object->db_autosave)
				{
					$object->save();
				}
			}
		}
		
		if (is_array(TDBM_Object::$objects))
		{
			foreach (TDBM_Object::$objects as $table)
			{
				if (is_array($table))
				{
					foreach ($table as $object)
					{
						if (!$object->db_onerror && $object->db_autosave)
						{
							$object->save();
						}
					}
				}
			}
			
			// Now, all the new objects should be added to the list of existing objects.
			// FIXME: We need to put the newobject into the object table.
			// To do this, we need the ID!!!!!!
			/*foreach ($saved_new_objects as $object) {
				if (!is_array(TDBM_Object::$objects[$object->db_table_name])) {
					TDBM_Object::$objects[$object->db_table_name] = array();
				}
				TDBM_Object::$objects[$object->db_table_name][$object->]
			}*/
			
		}
		
	}

	/**
	 * This function performs a save() of all the objects that have been modified just before the program exits.
	 * It should never be called by the user, the program will call it directly.
	 *
	 */
	static function completeSaveOnExit() {
		TDBM_Object::$is_program_exiting = true;
		TDBM_Object::completeSave();
		
		// Now, let's commit or rollback if needed.
		if (TDBM_Object::$main_db != null && !TDBM_Object::$main_db->isAutoCommit()) {
			if (TDBM_Object::$main_db->isCommitOnQuit()) {
				TDBM_Object::$main_db->commit();
			} else {
				TDBM_Object::$main_db->rollback();
			}
		}
	}

	/**
	 * This function performs a save() of all the objects that have been modified, then it sets all the data to a not loaded state.
	 * Therefore, the database will be queried again next time we access the object. Meanwhile, if another process modifies the database,
	 * the changes will be retrieved when we access the object again.
	 *
	 */
	static function completeSaveAndFlush() {
		TDBM_Object::completeSave();

		if (is_array(TDBM_Object::$objects))
		{
			foreach (TDBM_Object::$objects as $table)
			{
				if (is_array($table))
				{
					foreach ($table as $object)
					{
						if (!$object->db_onerror && $object->DBM_Object_state == "loaded")
						{
							$object->DBM_Object_state == "not loaded";
						}
					}
				}
			}
		}
	}

	/**
	 * Reverts any changes made to the object and resumes it to its DB state.
	 * This can only be called on objects that come from database adn that have not been deleted.
	 * Otherwise, this will throw an exception.
	 *
	 */
	public function discardChanges() {
		if ($this->DBM_Object_state == "new")
		throw new TDBM_Exception("You cannot call discardChanges() on an object that has been created with getNewObject and that has not yet been saved.");

		if ($this->DBM_Object_state == "deleted")
		throw new TDBM_Exception("You cannot call discardChanges() on an object that has been deleted.");
			
		$this->db_modified_state = false;
		$this->DBM_Object_state = "not loaded";
	}

	/**
	 * Removes the given object from database.
	 *
	 * @param TDBM_Object $object the object to delete.
	 */
	static public function deleteObject(TDBM_Object $object) {
		$object->DBM_Object_state == "deleted";
		if ($object->DBM_Object_state != "new")
		{
			//$primary_key = $object->getPrimaryKey();
			$pk_table = $object->getPrimaryKey();
			// Now for the object_id
			$object_id = $object->DBM_Object_id;
			// If there is only one primary key:
			if (count($pk_table)==1) {
				$sql_where = $pk_table[0]."=".$object->db_connection->quoteSmart($object->DBM_Object_id);
			} else {
				$ids = unserialize($object_id);
				$i=0;
				$sql_where_array = array();
				foreach ($pk_table as $pk) {
					$sql_where_array[] = $pk."=".$object->db_connection->quoteSmart($ids[$i]);
					$i++;
				}
				$sql_where = implode(" AND ",$sql_where_array);
			}


			$sql = 'DELETE FROM '.$object->db_table_name.' WHERE '.$sql_where/*.$primary_key."='".plainstring_to_dbprotected($object->DBM_Object_id)."'"*/;
			$result = TDBM_Object::$main_db->exec($sql);

			if ($result != 1)
			throw new TDBM_Exception("Error while deleting object from table ".$object->db_table_name.": ".$result." have been affected.");

			unset (TDBM_Object::$objects[$object->db_table_name][$object_id]);
		}
	}

	/**
	 * The getObjectsFromSQL is used to retrieve objects from the database using a full SQL query.
	 * The TDBM library is designed to make the SQL query instead of you.
	 * So in 80% of the cases, you should use the getObjects method, which does the work for you.
	 * The getObjectsFromSQL method should be used in those 20% cases where getObjects cannot be used.
	 * Please refer to the section "What you cannot do with TDBM" of the manual for more information.
	 *
	 * The getObjectsFromSQL method is passed the kind of objects you want to retrieve, the SQL of the query,
	 * and it returns a DBM_ObjectArray which is basically an array of DBM_Objects.
	 *
	 * Note that a TDBM_Object always map a row in a database. Therefore, your SQL query should return all the columns
	 * of the mapped table, and only those columns. A simple way of doing this is to use the "table.*" notation.
	 *
	 * For instance, is you have a "users" table with a "boss_id" column referencing your "user_id" primary key
	 * (to handle the hierarchy in an organization), and if you want to retrieve the employees that directly work
	 * for "John Doe", you would write:
	 * $users = getObjectsBySQL("SELECT u1.* FROM users u1 JOIN users u2 ON u1.boss_id = u2.user_id WHERE u2.user_name='John Doe'");
	 *
	 * Finally, you can specify the offset and the maximum number of objects returned to you using
	 * the from and the limit parameters.
	 *
	 * @param string $table_name The kind of objects that will be returned
	 * @param string $sql The SQL of the query
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of objects returned
	 * @return DBM_ObjectArray The result set of the query as a DBM_ObjectArray (an array of DBM_Objects with special properties)
	 */
	static public function getObjectsFromSQL($table_name, $sql, $from=null, $limit=null) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}

		$table_name = TDBM_Object::$main_db->toStandardcase($table_name);

		/*if (!isset(TDBM_Object::$primary_keys[$table_name]))
		 {
			TDBM_Object::$primary_keys[$table_name] = TDBM_Object::$main_db->getPrimaryKey($table_name);
			if (TDBM_Object::$primary_keys[$table_name] == false)
			{
			throw new TDBM_Exception("Could not find table primary key.");
			}
			}*/
		TDBM_Object::getPrimaryKeyStatic($table_name, TDBM_Object::$main_db);

		$result = TDBM_Object::$main_db->query($sql, array(), $from, $limit);

		$returned_objects = new DBM_ObjectArray();

		while ($fullCaseRow = $result->fetch(PDO::FETCH_ASSOC))
		{
			$row = array();
			foreach ($fullCaseRow as $key=>$value)  {
				$row[TDBM_Object::$main_db->toStandardCaseColumn($key)]=$value;
			}
			
			$pk_table = TDBM_Object::$primary_keys[$table_name];
			if (count($pk_table)==1)
			{
				$id = $row[$pk_table[0]];
			}
			else
			{
				// Let's generate the serialized primary key from the columns!
				$ids = array();
				foreach ($pk_table as $pk) {
					$ids[] = $row[$pk];
				}
				$id = serialize($ids);
			}

			if (!isset(TDBM_Object::$objects[$table_name][$id]))
			{
				TDBM_Object::$objects[$table_name][$id] = new TDBM_Object(TDBM_Object::$main_db, $table_name, $id);
				TDBM_Object::$objects[$table_name][$id]->loadFromRow($row);
			}
			$returned_objects[] = TDBM_Object::$objects[$table_name][$id];
		}

		return $returned_objects;
	}

	/**
	 * Returns transient objects.
	 * getTransientObjectsFromSQL executes the SQL request passed, and returns a set of objects matching this request.
	 * The objects returned will not be saved in database if they are modified.
	 *
	 * This method is particularly useful for retrieving aggregated data for instance (requests with GROUP BY).
	 *
	 * For instance you can use getTransientObjectsFromSQL to rertrieve the number of users in each country:
	 *
	 * $objects = getTransientObjectsFromSQL("SELECT country_code, count(user_id) AS cnt FROM users GROUP BY country_code");
	 * foreach ($objects as $object) {
	 * 		echo "Country $object->country_code has $object->cnt users";
	 * }
	 *
	 * Note that using getObjectsFromSQL for such requests would be a mistake since getObjectsFromSQL is retrieving objects
	 * that can be saved later.
	 *
	 * TODO: make the result a DBM_ObjectArray instead of an array.
	 *
	 * @param string $sql
	 * @return array the result of your query
	 */
	static public function getTransientObjectsFromSQL($sql) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		return TDBM_Object::$main_db->getAll($sql, null, DB_FETCHMODE_OBJECT);
	}

	/**
	 * Used to implent the get_XXX functions where XXX is a table name.
	 *
	 * @param unknown_type $func_name
	 * @param unknown_type $values
	 * @return unknown
	 */
	public function __call($func_name, $values) {

		if (strpos($func_name,"get_") === 0) {
			$table = substr($func_name,4);
		} else {
			throw new TDBM_Exception("Method ".$func_name." not found");
		}

		//return $this->cleverget($table, $values[0]);
		return TDBM_Object::getObjects($table, $this, null, null, null, $values[0]);
	}

	/**
	 * TODO
	 *
	 * @param unknown_type $table
	 * @param TDBM_Object $object
	 * @param unknown_type $column_hint
	 * @param unknown_type $fail_if_empty
	 * @return unknown
	 */
	private function set_one_star($table, TDBM_Object $object, $column_hint, $fail_if_empty) {
		$this->dbLoadIfNotLoaded();

		$arr_col = $this->getConstraintsFromTable($table, $fail_if_empty);

		// this can happen only if $fail_if_empty==false
		if ($arr_col == false)
		return false;

		if (count($arr_col)==1)
		{
			if ($column_hint!=null && $column_hint != $arr_col[0]['constraining_column'])
			throw new TDBM_Exception("The table $this->table does not have a foreign key on the column $column_hint.");

			$constraining_column = $arr_col[0]['constraining_column'];
			$constrained_column = $arr_col[0]['constrained_column'];
		}
		else
		{
			// If there are many columns, let's opt for the column hint if we have it.
			if ($column_hint == null)
			{
				throw new TDBM_Exception("There are many columns in $this->db_table_name referencing table $table. Please specify one.");
			}

			foreach ($arr_col as $constraint) {
				if ($column_hint == $constraint['constraining_column'])
				{
					$constraining_column = $constraint['constraining_column'];
					$constrained_column = $constraint['constrained_column'];
					break;
				}
			}
			if ($constrained_column == null)
			{
				throw new TDBM_Exception("The column $column_hint in table $this->db_table_name does not exist or does not hold a foreign key on $table.");
			}

		}

		// Setup the dependency:
		$this->db_dependency[$constraining_column] = $object;

		if ($object->DBM_Object_id)
		{
			$this->db_row[$constraining_column] = $object->DBM_Object_id;
		}
		else {
			unset($this->db_row[$constraining_column]);
		}
		// TO BE CONTINUED...
	}

	/**
	 * TO DO: remove this method after rewriting the behaviour of cleverget
	 *
	 * @param unknown_type $table1
	 * @param unknown_type $table2
	 * @param unknown_type $conn
	 * @return unknown
	 */
	/*private static function static_find_path($table1, $table2, &$conn) {
		if (isset($_SESSION['__TDBM_CACHE__']['paths'][$table1][$table2]))
		return $_SESSION['__TDBM_CACHE__']['paths'][$table1][$table2];

		$path = array();
		$queue = array(array($table1,array()));

		$found_paths=array();
		$found = false;
		$found_depth = 0;

		while (!empty($queue))
		{
		$ret = TDBM_Object::find_path_iter($table2,  $path, $queue, $conn);
		if ($found && $found_depth != count($path))
		{
		break;
		}
		if ($ret==true)
		{
		// Ok, we got one, we will continue a bit more until we reach the next level in the tree,
		// just to see if there is no ambiguity
		$found_paths[] = $path;
		$found = true;
		$found_depth = count($path);
		}
		}

		if (count($found_paths)==0) {
		throw new TDBM_Exception("Unable to find a path between table ".$table1." and table $table2.\nIt is likely that the table names are misspelled or that a constraint is missing.");
		}
		elseif (count($found_paths)>1) {
		$msg = "An ambiguity has been found during the search. The table \"$table2\" can be reach by several different ways from the table \"$table1\"\n\n".
		$count = 0;
		foreach ($found_paths as $path) {
		$count++;
		$msg .= "Solution $count:\n";
		$msg .= TDBM_Object::to_explain_string($path)."\n\n";
		}
			
		throw new TDBM_Exception($msg);
		}

		$_SESSION['__TDBM_CACHE__']['paths'][$table1][$table2] = $found_paths[0];
		$_SESSION['__TDBM_CACHE__']['paths'][$table2][$table1] = $found_paths[0];

		return $found_paths[0];
		}*/

	/**
	 * Finds the path from our table to the given table.
	 * Returns the path as a series of constraints defined by source and dest table, source and dest columns and the
	 * constraint type (1* or *1).
	 *
	 * @param unknown_type $table
	 * @return unknown
	 */
	public function find_path($table) {

		// $visited['table']['column'] => set to true if this constraint has been gone through while
		// going down the tree.

		//$current_table = $this->db_table_name;
		//$visited = array();
		return TDBM_Object::static_find_path($this->db_table_name, $table, $this->db_connection);


	}

	private static function to_explain_string($path) {
		$msg = '';
		foreach ($path as $constraint) {
			if ($constraint['type']=='1*') {
				$msg .= 'Table "'.$constraint['table1'].'" points to "'.$constraint['table2'].'" through its foreign key "'.$constraint['col1'].'"\n';
			}
			elseif ($constraint['type']=='*1') {
				$msg .= 'Table "'.$constraint['table2'].'" is pointed by "'.$constraint['table1'].'" through its foreign key "'.$constraint['col1'].'"\n';
			}
		}
		return $msg;
	}

	/**
	 * Returns an array of paths going from "$table" to the tables passed in the array "$tables"
	 *
	 * @param string $table The base table
	 * @param array $tables The destination tables
	 * @param unknown_type $conn
	 * @return unknown
	 */
	private static function static_find_paths($table, $tables, $conn=null) {
		if ($conn==null)
		$conn = TDBM_Object::$main_db;
		/*if (isset($_SESSION['__TDBM_CACHE__']['paths'][$table1][$table2]))
			return $_SESSION['__TDBM_CACHE__']['paths'][$table1][$table2];*/

		$path = array();
		$queue = array(array($table,array()));

		//$found_paths=array();
		$found = false;
		$found_depth = 0;

		$tables_paths = array();
		$cached_tables_paths = array();

		// Let's fill the $tables_paths that will contain the name of the tables needed (and the paths soon).
		// Also, let's use this moment to check if the tables we are looking for are not in cache.
		foreach ($tables as $tablename) {
			if (isset($_SESSION['__TDBM_CACHE__']['paths'][$table][$tablename]))
			{
				$cached_path = array();
				$cached_path['name'] = $tablename;
				$cached_path['founddepth'] = count($_SESSION['__TDBM_CACHE__']['paths'][$table][$tablename]);
				$cached_path['paths'][] = $_SESSION['__TDBM_CACHE__']['paths'][$table][$tablename];
				$cached_tables_paths[] = $cached_path;
			}
			elseif (isset($_SESSION['__TDBM_CACHE__']['paths'][$tablename][$table]))
			{
				$cached_path = array();
				$cached_path['name'] = $tablename;
				$cached_path['founddepth'] = count($_SESSION['__TDBM_CACHE__']['paths'][$tablename][$table]);
				$cached_path['paths'][] = $_SESSION['__TDBM_CACHE__']['paths'][$tablename][$table];
				$cached_tables_paths[] = $cached_path;
			}
			else
			$tables_paths[]['name'] = $tablename;
		}

		if (count($tables_paths)>0) {

			// Let's get the maximum execution time and let's take 90% of it:
			$max_execution_time = ini_get("max_execution_time")*0.9;

			while (!empty($queue))
			{
				$ret = TDBM_Object::find_paths_iter($tables_paths, $path, $queue, $conn);
				if ($found && $found_depth != count($path))
				{
					break;
				}
				if ($ret==true)
				{

					// Ok, we got one, we will continue a bit more until we reach the next level in the tree,
					// just to see if there is no ambiguity
					//$found_paths[] = $path;
					$found = true;
					$found_depth = count($path);
				}

				// At each iteration, let's check the time.
				if (microtime(true)-TDBM_Object::$script_start_up_time > $max_execution_time && $max_execution_time!=0) {
					// Call check table names
					TDBM_Object::checkTablesExist($tables);

					// If no excecution thrown we still have a risk to run out of time.
					throw new TDBM_Exception("Your request is too slow. 90% of the total amount of execution time allocated to this page has passed. Try to allocate more time for the execution of PHP pages by changing the max_execution_time parameter in php.ini");

				}
			}
		}

		$ambiguity =false;
		$msg = '';
		foreach ($tables_paths as $table_path) {
			// If any table has not been found, throw an exception
			if ($table_path['founddepth']==null) {
				// First, check if the tables do exist.
				TDBM_Object::checkTablesExist(array($table, $table_path['name']));
				// Else, throw an error.
				throw new TDBM_Exception("Unable to find a path between table ".$table." and table ".$table_path['name'].".\nIt is likely that a constraint is missing.");
			}
			// If any table has more than 1 way to be reached, throw an exception.
			if (count($table_path['paths'])>1) {
				// If this is the first ambiguity
				if (!$ambiguity)
				$msg .= 'An ambiguity has been found during the search. Please catch this exception and execute the $exception->explainAmbiguity() to get a nice graphical view of what you should do to solve this ambiguity.';

				$msg .= "The table \"".$table_path['name']."\" can be reached using several different ways from the table \"$table\".\n\n";
				$count = 0;
				foreach ($table_path['paths'] as $path) {
					$count++;
					$msg .= "Solution $count:\n";
					$msg .= TDBM_Object::to_explain_string($path)."\n\n";
				}

				$ambiguity = true;

				//throw new TDBM_Exception($msg);
				//throw new TDBM_AmbiguityException($msg, $tables_paths);
			}

			if (!$ambiguity)
			$_SESSION['__TDBM_CACHE__']['paths'][$table][$table_path['name']] = $table_path['paths'][0];
		}

		$tables_paths = array_merge($tables_paths, $cached_tables_paths);

		if ($ambiguity)
		throw new TDBM_AmbiguityException($msg, $tables_paths);

		//var_dump($tables_paths);
		return $tables_paths;

	}

	/**
	 * This function takes an array of paths in parameter and flatten the paths into only one
	 * path while eliminating doublons.
	 * A-B/B-C
	 * and			=>	A-B/B-C/B-D
	 * A-B/B-D
	 *
	 * @param unknown_type $paths
	 */
	private static function flatten_paths($paths) {
		$flat_path=array();
		foreach ($paths as $path_bigarray) {
			$path = $path_bigarray['paths'][0];

			foreach ($path as $path_step) {
				$found = false;
				foreach ($flat_path as $path_step_verify) {
					if ($path_step == $path_step_verify) {
						$found = true;
						break;
					}
				}
				if (!$found)
				$flat_path[] = $path_step;
			}
		}
		return $flat_path;
	}

	/**
	 * Iterative function used by static_find_paths.
	 *
	 * @param unknown_type $target_tables
	 * @param unknown_type $path
	 * @param unknown_type $queue
	 * @param unknown_type $conn
	 * @return unknown
	 */
	private static function find_paths_iter(&$target_tables, &$path, &$queue, &$conn) {
		// Get table to look at:
		$current_vars = array_shift($queue);
		$current_table = $current_vars[0];
		$path = $current_vars[1];

		//echo '-'.$current_table.'-';
		//echo '.';
		foreach ($target_tables as $id=>$target_table) {
			if ($target_table['name'] == $current_table && ($target_table['founddepth']==null || $target_table['founddepth']==count($path))) {
				// When a path is found to a table, we mark the table as found with its depth.
				$target_tables[$id]['founddepth']=count($path);

				// Then we add the path to table to the target_tables array
				$target_tables[$id]['paths'][] = $path;
				//echo "found: ".$target_table;
				// If all tables have been found, return true!
				$found = true;
				foreach ($target_tables as $test_table) {
					if ($test_table['founddepth'] == null) {
						$found = false;
					}
				}

				if ($found)
				return true;
			}

		}

		/*if ($target_table == $current_table) {
			return true;
			}*/

		// Let's start with 1*
		$constraints = $conn->getConstraintsFromTableWithCache($current_table);

		foreach ($constraints as $constraint) {

			$table1 = $constraint['table1'];
			$col1 = $constraint['col1'];
			$col2 = $constraint['col2'];

			/*if ($visited[$table1][$col1]==true)
				continue;
				else
				$visited[$table1][$col1]=true;*/
			// Go through the path to see if we ever have gone through this link
			$already_done = false;
			foreach ($path as $previous_constraint)
			{
				if ($previous_constraint['type']=='1*' && $current_table == $previous_constraint["table2"] && $col2 == $previous_constraint["col2"])
				{
					//echo "YOUHOU1! $current_table $col2";
					$already_done = true;
					break;
				}
				elseif ($previous_constraint['type']=='*1' && $current_table == $previous_constraint["table1"] && $col2 == $previous_constraint["col1"])
				{
					//echo "YOUHOU2! $current_table $col2";
					$already_done = true;
					break;
				}
			}
			if ($already_done)
			continue;

			$new_path = array_merge($path, array(array("table1"=>$table1,
									"col1"=>$col1,
									"table2"=>$current_table,
									"col2"=>$col2,
									"type"=>"1*")));
			array_push($queue, array($table1, $new_path));
		}

		// Let's continue with *1
		$constraints = $conn->getConstraintsOnTableWithCache($current_table);

		foreach ($constraints as $constraint) {
			$table2 = $constraint['table2'];
			$col2 = $constraint['col2'];
			$col1 = $constraint['col1'];
			/*if ($visited[$table2][$col2]==true)
				continue;
				else
				$visited[$table2][$col2]=true;*/
			$already_done = false;
			foreach ($path as $previous_constraint)
			{
				//echo "TTTT".$table2." ".$col2."AAAA".$previous_constraint["table1"]." ".$previous_constraint["col1"]."YYYY".$previous_constraint["type"]."PPP";
				if ($previous_constraint['type']=='1*' && $table2 == $previous_constraint["table2"] && $col2 == $previous_constraint["col2"])
				{
					//echo  "YOUHOU3! $table2 $col2";
					$already_done = true;
					break;
				}
				elseif ($previous_constraint['type']=='*1' && $table2 == $previous_constraint["table1"] && $col2 == $previous_constraint["col1"])
				{
					//echo "YOUHOU4! $table2 $col2";
					$already_done = true;
					break;
				}
			}
			if ($already_done)
			continue;

			$new_path = array_merge($path, array(array("table1"=>$table2,
									"col1"=>$col2,
									"table2"=>$current_table,
									"col2"=>$col1,
									"type"=>"*1")));
			array_push($queue, array($table2, $new_path));
		}

		return false;
	}

	/**
	 * Returns an array of objects of "table_name" kind filtered from the filter bag.
	 *
	 * The getObjects method should be the most used query method in TDBM if you want to query the database for objects.
	 * (Note: if you want to query the database for an object by its primary key, use the getObject method).
	 *
	 * The getObjects method takes in parameter:
	 * 	- table_name: the kinf of TDBM_Object you want to retrieve. In TDBM, a TDBM_Object matches a database row, so the
	 * 			$table_name parameter should be the name of an existing table in database.
	 *  - filter_bag: The filter bag is anything that you can use to filter your request. It can be a SQL Where clause,
	 * 			a series of DBM_Filter objects, or even DBM_Objects or DBM_ObjectArrays that you will use as filters.
	 *  - order_bag: The order bag is anything that will be used to order the data that is passed back to you.
	 * 			A SQL Order by clause can be used as an order bag for instance, or a DBM_OrderByColumn object
	 * 	- from (optionnal): The offset from which the query should be performed. For instance, if $from=5, the getObjects method
	 * 			will return objects from the 6th rows.
	 * 	- limit (optionnal): The maximum number of objects to return. Used together with $from, you can implement
	 * 			paging mechanisms.
	 *  - hint_path (optionnal): EXPERTS ONLY! The path the request should use if not the most obvious one. This parameter
	 * 			should be used only if you perfectly know what you are doing.
	 *
	 * The getObjects method will return a DBM_ObjectArray. A DBM_ObjectArray is an array of DBM_Objects that does behave as
	 * a single TDBM_Object if the array has only one member. Refer to the documentation of DBM_ObjectArray and TDBM_Object
	 * to learn more.
	 *
	 * More about the filter bag:
	 * A filter is anything that can change the set of objects returned by getObjects.
	 * There are many kind of filters in TDBM:
	 * A filter can be:
	 * 	- A SQL WHERE clause:
	 * 		The clause is specified without the "WHERE" keyword. For instance:
	 * 			$filter = "users.first_name LIKE 'J%'";
	 *     	is a valid filter.
	 * 	   	The only difference with SQL is that when you specify a column name, it should always be fully qualified with
	 * 		the table name: "country_name='France'" is not valid, while "countries.country_name='France'" is valid (if
	 * 		"countries" is a table and "country_name" a column in that table, sure.
	 * 		For instance,
	 * 				$french_users = TDBM_Object::getObjects("users", "countries.country_name='France'");
	 * 		will return all the users that are French (based on trhe assumption that TDBM can find a way to connect the users
	 * 		table to the country table using foreign keys, see the manual for that point).
	 * 	- A TDBM_Object:
	 * 		An object can be used as a filter. For instance, we could get the France object and then find any users related to
	 * 		that object using:
	 * 				$france = TDBM_Object::getObjects("country", "countries.country_name='France'");
	 * 				$french_users = TDBM_Object::getObjects("users", $france);
	 *  - A DBM_ObjectArray can be used as a filter too.
	 * 		For instance:
	 * 				$french_groups = TDBM_Object::getObjects("groups", $french_users);
	 * 		might return all the groups in which french users can be found.
	 *  - Finally, DBM_xxxFilter instances can be used.
	 * 		TDBM provides the developer a set of DBM_xxxFilters that can be used to model a SQL Where query.
	 * 		Using the appropriate filter object, you can model the operations =,<,<=,>,>=,IN,LIKE,AND,OR, IS NULL and NOT
	 * 		For instance:
	 * 				$french_users = TDBM_Object::getObjects("users", new DBM_EqualFilter('countries','country_name','France');
	 * 		Refer to the documentation of the appropriate filters for more information.
	 *
	 * The nice thing about a filter bag is that it can be any filter, or any array of filters. In that case, filters are
	 * 'ANDed' together.
	 * So a request like this is valid:
	 * 				$france = TDBM_Object::getObjects("country", "countries.country_name='France'");
	 * 				$french_administrators = TDBM_Object::getObjects("users", array($france,"role.role_name='Administrators'");
	 * This requests would return the users that are both French and administrators.
	 *
	 * Finally, if filter_bag is null, the whole table is returned.
	 *
	 * More about the order bag:
	 * The order bag contains anything that can be used to order the data that is passed back to you.
	 * The order bag can contain two kinds of objects:
	 * 	- A SQL ORDER BY clause:
	 * 		The clause is specified without the "ORDER BY" keyword. For instance:
	 * 			$orderby = "users.last_name ASC, users.first_name ASC";
	 *     	is a valid order bag.
	 * 		The only difference with SQL is that when you specify a column name, it should always be fully qualified with
	 * 		the table name: "country_name ASC" is not valid, while "countries.country_name ASC" is valid (if
	 * 		"countries" is a table and "country_name" a column in that table, sure.
	 * 		For instance,
	 * 				$french_users = TDBM_Object::getObjects("users", null, "countries.country_name ASC");
	 * 		will return all the users sorted by country.
	 *  - A DBM_OrderByColumn object
	 * 		This object models a single column in a database.
	 *
	 * @param string $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see above for complete description)
	 * @param unknown_type $orderby_bag The order bag (see above for complete description)
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of rows returned
	 * @param unknown_type $hint_path Hints to get the path for the query (expert parameter, you should leave it to null).
	 * @return DBM_ObjectArray A DBM_ObjectArray containing the resulting objects of the query.
	 */
	static public function getObjects($table_name, $filter_bag=null, $orderby_bag=null, $from=null, $limit=null, $hint_path=null) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		return TDBM_Object::getObjectsByMode('getObjects', $table_name, $filter_bag, $orderby_bag, $from, $limit, $hint_path);
	}

	/**
	 * Performs a request and returns only the number of records returned from the database, applying the filterbag.
	 * This function takes essentially the same parameters as the getObjects function (at least the same $filter_bag).
	 *
	 * @param unknown_type $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see getObjects for complete description)
	 * @param unknown_type $hint_path
	 * @return integer
	 */
	static public function getCount($table_name, $filter_bag=null, $hint_path=null) {
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		return TDBM_Object::getObjectsByMode('getCount', $table_name, $filter_bag, null, null, null, $hint_path);
	}

	/**
	 * Returns the SQL that would be used by getObjects if it was called with the same parameters.
	 *
	 * @param string $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see above for complete description)
	 * @param unknown_type $orderby_bag The order bag (see above for complete description)
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of rows returned
	 * @param unknown_type $hint_path Hints to get the path for the query (expert parameter, you should leave it to null).
	 * @return string The SQL that would be executed.
	 */
	static public function explainSQLGetObjects($table_name, $filter_bag=null, $orderby_bag=null, $from=null, $limit=null, $hint_path=null) 	{
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		return TDBM_Object::getObjectsByMode('explainSQL', $table_name, $filter_bag, $orderby_bag, $from, $limit, $hint_path);
	}

	/**
	 * Returns the "jointure-tree" that would be used by getObjects if it was called with the same parameters as text (human readable).
	 *
	 * @param string $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see above for complete description)
	 * @param unknown_type $orderby_bag The order bag (see above for complete description)
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of rows returned
	 * @param unknown_type $hint_path Hints to get the path for the query (expert parameter, you should leave it to null).
	 * @return string The SQL that would be executed.
	 */
	static public function explainRequestAsTextGetObjects($table_name, $filter_bag=null, $orderby_bag=null, $from=null, $limit=null, $hint_path=null) 	{
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		$tree = TDBM_Object::getObjectsByMode('explainTree', $table_name, $filter_bag, $orderby_bag, $from, $limit, $hint_path);
		return $tree->displayText();
	}


	/**
	 * Returns the "jointure-tree" that would be used by getObjects if it was called with the same parameters as HTML.
	 * Just "echo" this text to an HTML page to get a drawing of the request performed.
	 *
	 * @param string $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see above for complete description)
	 * @param unknown_type $orderby_bag The order bag (see above for complete description)
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of rows returned
	 * @param unknown_type $hint_path Hints to get the path for the query (expert parameter, you should leave it to null).
	 * @return string The SQL that would be executed.
	 */
	static public function explainRequestAsHTMLGetObjects($table_name, $filter_bag=null, $orderby_bag=null, $from=null, $limit=null, $hint_path=null, $x=10, $y=10) 	{
		if (TDBM_Object::$main_db == null) {
			throw new TDBM_Exception("Error while calling TDBM_Object::getObject(): No connection has been established on the database!");
		}
		$tree = TDBM_Object::getObjectsByMode('explainTree', $table_name, $filter_bag, $orderby_bag, $from, $limit, $hint_path);
		return TDBM_Object::drawTree($tree,$x,$y);
	}

	/**
	 * Performs the real operations for getObjects, explainSQL and explainTree.
	 * It takes as an entry the same parameters, with an additional parameter $mode.
	 *
	 * @param string $mode One of 'getObjects', 'explainSQL', 'explainTree'
	 * @param string $table_name The name of the table queried
	 * @param unknown_type $filter_bag The filter bag (see above for complete description)
	 * @param unknown_type $orderby_bag The order bag (see above for complete description)
	 * @param integer $from The offset
	 * @param integer $limit The maximum number of rows returned
	 * @param unknown_type $hint_path Hints to get the path for the query (expert parameter, you should leave it to null).
	 * @return DBM_ObjectArray A DBM_ObjectArray containing the resulting objects of the query.
	 */
	static public function getObjectsByMode($mode, $table_name, $filter_bag=null, $orderby_bag=null, $from=null, $limit=null, $hint_path=null) {
		TDBM_Object::completeSave();

		
		// Let's get the filter from the filter_bag
		$filter = self::buildFilterFromFilterBag($filter_bag);

		// Let's get the order array from the order_bag
		$orderby_bag2 = self::buildOrderArrayFromOrderBag($orderby_bag);
		
		// Now, let's find the path from the needed tables of the resulting filter.

		// Let's get needed tables from the filters
		$needed_table_array_for_filters = $filter->getUsedTables();

		$needed_table_array_for_orderby = array();
		// Let's get needed tables from the order by
		foreach ($orderby_bag2 as $orderby) {
			$needed_table_array_for_orderby = array_merge($needed_table_array_for_orderby, $orderby->getUsedTables());
		}

		// Remove the asked table from the needed table array for group bys.
		foreach ($needed_table_array_for_orderby as $id=>$needed_table_name)
		{
			if ($needed_table_name == $table_name) {
				unset($needed_table_array_for_orderby[$id]);
			}
		}

		$needed_table_array = array_flip(array_flip(array_merge($needed_table_array_for_filters, $needed_table_array_for_orderby)));

		// Remove the asked table from the needed table array.
		foreach ($needed_table_array as $id=>$needed_table_name)
		{
			if ($needed_table_name == $table_name) {
				unset($needed_table_array[$id]);
			}
		}

		if (count($needed_table_array)==0)
		{
			$table_number = 1;
			$sql = $table_name;

			if ($mode == 'explainTree')
			throw new TDBM_Exception("TODO: explainTree not implemented for only one table.");
		}
		else {
			if ($hint_path!=null && $mode != 'explainTree')
			{
				$path = $hint_path;
				$flat_path = TDBM_Object::flatten_paths($path);
			}
			else
			{
				$full_paths = TDBM_Object::static_find_paths($table_name,$needed_table_array);

				if ($mode == 'explainTree') {
					return TDBM_Object::getTablePathsTree($full_paths);
				}

				$flat_path = TDBM_Object::flatten_paths($full_paths);
			}

			// Now, let's generate the SQL and let's call getObjectsBySQL.

			//print_r($flat_path);

			$constraint = $flat_path[0];

			//$table_number=1;
			$sql = $constraint['table2'];

			foreach ($flat_path as $constraint) {
				//$previous_table_number = $table_number;
				//$table_number++;
				$table1 = $constraint['table2'];
				$table2 = $constraint['table1'];
				$col2 = $constraint['col1'];
				$col1 = $constraint['col2'];
					
				$sql = "($sql LEFT JOIN ".$table2." ON
				$table1.$col1=$table2.$col2)";
			}
		}


		// Now, for each needed table to perform the order by, we must verify if the relationship between the order by and the object is indeed a 1* relationship
		foreach ($needed_table_array_for_orderby as $target_table_table) {
			// Get the path between the main table and the target group by table

			// TODO! Pas bon!!!! Faut le quÃ©rir, hÃ©las!
			// Mais comment gÃ©rer Ã§a sans plomber les perfs et en utilisant le path fourni?????

			$path = $_SESSION['__TDBM_CACHE__']['paths'][$table_name][$target_table_table];
			/*echo 'beuuuh';
			 var_dump($needed_table_array_for_orderby);
			 var_dump($path);*/
			$is_ok = true;
			foreach ($path as $step) {
				if ($step["type"]=="*1") {
					$is_ok = false;
					break;
				}
			}

			if (!$is_ok) {
				throw new TDBM_Exception("Error in querying database from getObjectsByFilter. You tried to order your data according to a column of the '$target_table_table' table. However, the '$target_table_table' table has a many to 1 relationship with the '$table_name' table. This means that one '$table_name' object can contain many '$target_table_table' objects. Therefore, trying to order '$table_name' objects using '$target_table_table' objects is meaningless and cannot be performed.");
			}
		}

		// In a SELECT DISTINCT ... ORDER BY ... clause, the orderbyed columns must appear!
		// Therefore, we must be able to parse the Orderby columns requested, give them dummy names and remove them afterward!
		// Get the column statement and the order by statement
		$orderby_statement = '';
		$orderby_column_statement = '';

		if (count($orderby_bag2)>0) {

			// make an array of columns
			$orderby_columns_array = array();
			foreach ($orderby_bag2 as $orderby_object) {
				$orderby_columns_array = array_merge($orderby_columns_array, $orderby_object->toSqlStatementsArray());
			}

			$orderby_statement = ' ORDER BY '.implode(',',$orderby_columns_array);
			$count = 0;
			foreach ($orderby_columns_array as $id=>$orderby_statement_phrase) {
				// Let's remove the trailing ASC or DESC and add AS tdbm_reserved_col_Xxx
				$res = strripos($orderby_statement_phrase, 'ASC');
				if ($res !== false) {
					$orderby_statement_phrase = substr($orderby_statement_phrase, 0, $res);
				} else {
					$res = strripos($orderby_statement_phrase, 'DESC');
					if ($res !== false) {
						$orderby_statement_phrase = substr($orderby_statement_phrase, 0, $res);
					}
				}


				$orderby_columns_array[$id] = $orderby_statement_phrase.' AS tdbm_reserved_col_'.$count;
				$count++;
			}
			$orderby_column_statement = ', '.implode(',',$orderby_columns_array);
		}

		if ($mode=="getCount") {
			// TODO: select count might not perform the required DISTINCT!
			$sql = "SELECT COUNT(1) FROM $sql";

			$where_clause = $filter->toSql();
			if ($where_clause != '')
			$sql .= ' WHERE '.$where_clause;

			// Now, let's perform the request:
			$result = TDBM_Object::$main_db->getOne($sql, array());

			return $result;
		}

		$sql = "SELECT DISTINCT $table_name.* $orderby_column_statement FROM $sql";

		$where_clause = $filter->toSql();
		if ($where_clause != '')
		$sql .= ' WHERE '.$where_clause;

		$sql .= $orderby_statement;

			
		if ($mode == 'explainSQL') {
			return $sql;
		}
		return TDBM_Object::getObjectsFromSQL($table_name, $sql,  $from, $limit);

	}

	/**
	 * Takes in input a filter_bag (which can be about anything from a string to an array of DBM_Objects... see above from documentation), 
	 * and gives back a proper Filter object.
	 *
	 * @param unknown_type $filter_bag
	 * @return DBM_AbstractFilter
	 */
	static public function buildFilterFromFilterBag($filter_bag) {
		// First filter_bag should be an array, if it is a singleton, let's put it in an array.
		if (!is_array($filter_bag))
		$filter_bag = array($filter_bag);
		elseif (is_a($filter_bag, 'DBM_ObjectArray'))
		$filter_bag = array($filter_bag);

		// Second, let's take all the objects out of the filter bag, and let's make filters from them
		$filter_bag2 = array();
		foreach ($filter_bag as $thing) {
			if (is_a($thing,'DBM_AbstractFilter')) {
				$filter_bag2[] = $thing;
			} elseif (is_a($thing,'TDBM_Object')) {
				$pk_table = $thing->getPrimaryKey();
				// If there is only one primary key:
				if (count($pk_table)==1) {
					//$sql_where = "t1".$pk_table[0]."=".$this->db_connection->quoteSmart($this->DBM_Object_id);
					$filter_bag2[] = new DBM_EqualFilter($thing->db_table_name, $pk_table[0], $thing->$pk_table[0]);
				} else {
					//$ids = unserialize($this->DBM_Object_id);
					//$i=0;
					$filter_bag_temp_and=array();
					foreach ($pk_table as $pk) {
						$filter_bag_temp_and[] = new DBM_EqualFilter($thing->db_table_name, $pk, $thing->$pk);
					}
					$filter_bag2[] = new DBM_AndFilter($filter_bag_temp_and);
					//$sql_where = implode(" AND ",$sql_where_array);
				}
				//$primary_key = $thing->getPrimaryKey();

				//$filter_bag2[] = new DBM_EqualFilter($thing->db_table_name, $primary_key, $thing->$primary_key);
			} elseif (is_string($thing)) {
				$filter_bag2[] = new DBM_SQLStringFilter($thing);
			} elseif (is_a($thing,'DBM_ObjectArray') && count($thing)>0) {
				// Get table_name and column_name
				$filter_table_name = $thing[0]->db_table_name;
				$filter_column_names = $thing[0]->getPrimaryKey();

				// If there is only one primary key, we can use the InFilter
				if (count($filter_column_names)==1) {
					$primary_keys_array = array();
					$filter_column_name = $filter_column_names[0];
					foreach ($thing as $dbm_object) {
						$primary_keys_array[] = $dbm_object->$filter_column_name;
					}
					$filter_bag2[] = new DBM_InFilter($filter_table_name, $filter_column_name, $primary_keys_array);
				}
				// else, we must use a (xxx AND xxx AND xxx) OR (xxx AND xxx AND xxx) OR (xxx AND xxx AND xxx)...
				else
				{
					$filter_bag_and = array();
					foreach ($thing as $dbm_object) {
						$filter_bag_temp_and=array();
						foreach ($filter_column_names as $pk) {
							$filter_bag_temp_and[] = new DBM_EqualFilter($dbm_object->db_table_name, $pk, $dbm_object->$pk);
						}
						$filter_bag_and[] = new DBM_AndFilter($filter_bag_temp_and);
					}
					$filter_bag2[] = new DBM_OrFilter($filter_bag_and);
				}


			} elseif (!is_a($thing,'DBM_ObjectArray') && $thing!==null) {
				throw new TDBM_Exception("Error in filter bag in getObjectsByFilter. An object has been passed that is neither a filter, nor a TDBM_Object, nor a DBM_ObjectArray, nor a string, nor null.");
			}
		}

		// Third, let's take all the filters and let's apply a huge AND filter
		$filter = new DBM_AndFilter($filter_bag2);
		
		return $filter;
	}
	
	/**
	 * Takes in input an order_bag (which can be about anything from a string to an array of DBM_OrderByColumn objects... see above from documentation), 
	 * and gives back an array of DBM_OrderByColumn / DBM_OrderBySQLString objects.
	 *
	 * @param unknown_type $orderby_bag
	 * @return array
	 */
	static public function buildOrderArrayFromOrderBag($orderby_bag) {
		// Fourth, let's apply the same steps to the orderby_bag
		// 4-1 orderby_bag should be an array, if it is a singleton, let's put it in an array.

		if (!is_array($orderby_bag))
			$orderby_bag = array($orderby_bag);

		// 4-2, let's take all the objects out of the orderby bag, and let's make objects from them
		$orderby_bag2 = array();
		foreach ($orderby_bag as $thing) {
			if (is_a($thing,'DBM_OrderBySQLString')) {
				$orderby_bag2[] = $thing;
			} elseif (is_a($thing,'DBM_OrderByColumn')) {
				$orderby_bag2[] = $thing;
			} elseif (is_string($thing)) {
				$orderby_bag2[] = new DBM_OrderBySQLString($thing);
			} elseif ($thing !== null) {
				throw new TDBM_Exception("Error in orderby bag in getObjectsByFilter. An object has been passed that is neither a DBM_OrderBySQLString, nor a DBM_OrderByColumn, nor a string, nor null.");
			}
		}
		return $orderby_bag2;
	}
	
	/**
	 * Takes in entry an array of table names.
	 * Throws a TDBM_Exception if one of those table does not exist.
	 *
	 * @param unknown_type $tables
	 */
	private static function checkTablesExist($tables) {
		foreach ($tables as $table) {
			$possible_tables = TDBM_Object::$main_db->checkTableExist($table);
			if ($possible_tables !== true)
			{
				if (count($possible_tables)==1)
				$str = "Could not find table '$table'. Maybe you meant this table: '".$possible_tables[0]."'";
				else
				$str = "Could not find table '$table'. Maybe you meant one of those tables: '".implode("', '",$possible_tables)."'";
				throw new TDBM_Exception($str);
			}
		}
	}


	/**
	 * This function returns a DBM_DisplayNode tree modeling the $table_path.
	 *
	 * @param unknown_type $table_paths
	 */
	public static function getTablePathsTree($table_paths) {
		//var_dump($table_paths);
		$tree = new DBM_DisplayNode($table_paths[0]['paths'][0][0]['table2']);

		/*if ($table_paths[0]['paths'][0][0]['link']=='*1')
			$tree = new DBM_DisplayNode($table_paths[0]['paths'][0][0]['table2']);
			else
			$tree = new DBM_DisplayNode($table_paths[0]['paths'][0][0]['table1']);*/

		foreach ($table_paths as $table_path) {
			$path = $table_path['paths'][0];

			// We should create the tree, and at each pass, go down as far as we can in the tree.
			// If we can't go further, we add nodes.
			$current_node = $tree;
			$found = true;
			foreach ($path as $link) {
				if ($found==true)
				{
					if (is_array($current_node->getChildren()))
					{
						foreach ($current_node->getChildren() as $child)
						{
							if ($link['table1']==$child->table_name &&
							$link['col1']==$child->keyNode &&
							$link['col2']==$child->keyParent &&
							$link['type']==$child->link_type) {
								$current_node = $child;
							}
							else
							{
								// Now, we must add the rest of the links to the tree.
								$found = false;
							}
						}
					}
					else
					$found = false;

				}

				if ($found==false)
				{
					$current_node = new DBM_DisplayNode($link['table1'], $current_node, $link['type'], $link['col2'], $link['col1']);
					/*if ($link['type']=='*1')
						$current_node = new DBM_DisplayNode($link['table1'], $current_node, $link['type'], $link['col2'], $link['col1']);
						else
						$current_node = new DBM_DisplayNode($link['table2'], $current_node, $link['type'], $link['col1'], $link['col2']);*/
				}
			}

		}

		$tree->computeWidth();

		return $tree;

	}

	/**
	 * This function returns the HTML to draw a tree of DBM_DisplayNode.
	 *
	 * @param unknown_type $tree
	 */
	static public function drawTree($tree, $x, $y, &$ret_width=0, &$ret_height=0) {

		// Let's get the background div:
		$treeDepth = $tree->computeDepth(1)-1;
		$treeWidth = $tree->width;

		$ret_width = ($treeWidth*(DBM_DisplayNode::$box_width+DBM_DisplayNode::$interspace_width)+DBM_DisplayNode::$border*4-DBM_DisplayNode::$interspace_width);
		$ret_height = ($treeDepth*(DBM_DisplayNode::$box_height+DBM_DisplayNode::$interspace_height)+DBM_DisplayNode::$border*4-DBM_DisplayNode::$interspace_height);

		$str = "<div style='position:absolute; left:".($x+DBM_DisplayNode::$left_start-DBM_DisplayNode::$border)."px; top:".($y+DBM_DisplayNode::$top_start-DBM_DisplayNode::$border)."px; width:".$ret_width."px; height:".$ret_height."; background-color:#EEEEEE; color: white; text-align:center;'></div>";

		$str .= $tree->draw(0,0, $x, $y);

		return $str;

	}
}

class DBM_DisplayNode {
	public static $left_start = 0;
	public static $top_start = 0;
	public static $box_width = 250;
	public static $box_height = 30;
	public static $interspace_width = 10;
	public static $interspace_height = 50;
	public static $text_height=13;
	public static $border =2;


	private $parent_node;
	public $table_name;
	public $link_type;
	public $keyParent;
	public $keyNode;
	private $children;

	public $width;

	public function __construct($table_name, $parent_node=null, $link_type=null, $keyParent=null, $keyNode=null) {
		$this->table_name = $table_name;
		if ($parent_node !== null) {
			$this->parent_node = $parent_node;
			$parent_node->children[] = $this;
			$this->link_type = $link_type;
			$this->keyParent = $keyParent;
			$this->keyNode = $keyNode;
		}
	}

	public function getChildren() {
		return $this->children;
	}

	public function displayText() {
		if ($this->parent_node !== null)
		{
			if ($this->link_type == "*1")
			{
				echo "Table $this->table_name points to table ".$this->parent_node->table_name." through its foreign key on column $this->keyNode that points to column $this->keyParent<br />";
			}
			else if ($this->link_type == "1*")
			{
				echo "Table $this->table_name is pointed by table ".$this->parent_node->table_name." by its foreign key on column $this->keyParent that points to column $this->keyNode<br />";
			}
		}

			
		if (is_array($this->children)) {
			foreach ($this->children as $child) {
				$child->displayText();
			}
		}
	}

	public function computeWidth() {
		if (!is_array($this->children) || count($this->children)==0) {
			$this->width = 1;
			return 1;
		} else {
			$sum = 0;
			foreach ($this->children as $child) {
				$sum += $child->computeWidth();
			}
			$this->width = $sum;
			return $sum;
		}
	}

	public function computeDepth($my_depth) {
		if (!is_array($this->children) || count($this->children)==0) {
			return $my_depth+1;
		} else {
			$max = 0;
			foreach ($this->children as $child) {
				$depth = $my_depth + $child->computeDepth($my_depth);
				if ($depth > $max) {
					$max = $depth;
				}
			}
			return $max;
		}
	}

	public function draw($x, $y, $left_px, $top_px) {

		$mybox_width_px = $this->width*DBM_DisplayNode::$box_width + ($this->width-1)*DBM_DisplayNode::$interspace_width;
		$my_x_px = $left_px + DBM_DisplayNode::$left_start + $x*(DBM_DisplayNode::$box_width + DBM_DisplayNode::$interspace_width);
		$my_y_px = $top_px + DBM_DisplayNode::$top_start + $y*(DBM_DisplayNode::$box_height + DBM_DisplayNode::$interspace_height);

		// White background first
		/*$str = "<div style='position:absolute; left:".$my_x_px."px; top:".$my_y_px."px; width:".($mybox_width_px+$interspace_width)."px; height:".($box_height+$interspace_height)."; background-color:white;'></div>";
		*/
		$str .= "<div style='position:absolute; left:".$my_x_px."px; top:".$my_y_px."px; width:".$mybox_width_px."px; height:".DBM_DisplayNode::$box_height."; background-color:gray; color: white; text-align:center; border:".DBM_DisplayNode::$border."px solid black'>\n<b>".$this->table_name."</b></div>";

		if ($this->keyParent != null) {
			$my_x_px_line = $my_x_px + DBM_DisplayNode::$box_width/2;
			$my_y_px_line = $my_y_px - DBM_DisplayNode::$interspace_height;
			$str .= "<div style='position:absolute; left:".$my_x_px_line."px; top:".($my_y_px_line+DBM_DisplayNode::$border)."px; width:2px; height:".(DBM_DisplayNode::$interspace_height-DBM_DisplayNode::$border)."; background-color:black; '></div>\n";

			$top_key = ($this->link_type=='1*')?'* fk:':'1 pk:';
			$top_key .= '<i>'.$this->keyParent.'</i>';


			$bottom_key = ($this->link_type=='*1')?'* fk:':'1 pk:';
			$bottom_key .= '<i>'.$this->keyParent.'</i>';

			$str .= "<div style='position:absolute; left:".($my_x_px_line+2)."px; top:".($my_y_px_line+DBM_DisplayNode::$border*2)."px; background-color:#EEEEEE; font-size: 10px'>$top_key</div>\n";
			$str .= "<div style='position:absolute; left:".($my_x_px_line+2)."px; top:".($my_y_px_line+DBM_DisplayNode::$interspace_height-DBM_DisplayNode::$text_height)."px; background-color:#EEEEEE; font-size: 10px'>$bottom_key</div>\n";
		}
		//echo '<div style="position:absolute; left:100; top:70; width:2; height:20; background-color:blue"></div>';

		if (is_array($this->children)) {
			$x_new = $x;
			foreach ($this->children as $child) {
				$str .= $child->draw($x_new, $y+1, $left_px, $top_px);
				$x_new += $child->width;
			}
		}
		return $str;
	}
}

/**
 * An object that behaves just like an array of DBM_Objects.
 * If there is only one object in it, it can be accessed just like an object.
 *
 */
class DBM_ObjectArray extends ArrayObject {
	public function __get($var) {
		$cnt = count($this);
		if ($cnt==1)
		{
			return $this[0]->__get($var);
		}
		elseif ($cnt>1)
		{
			throw new TDBM_Exception('Array contains many objects! Use getarray_'.$var.' to retrieve an array of '.$var);
		}
		else
		{
			throw new TDBM_Exception('Array contains no objects');
		}
	}

	public function __set($var, $value) {
		$cnt = count($this);
		if ($cnt==1)
		{
			return $this[0]->__set($var, $value);
		}
		elseif ($cnt>1)
		{
			throw new TDBM_Exception('Array contains many objects! Use setarray_'.$var.' to set the array of '.$var);
		}
		else
		{
			throw new TDBM_Exception('Array contains no objects');
		}
	}

	/**
	 * getarray_column_name returns an array containing the values of the column of the given objects.
	 * setarray_column_name sets the value of the given column for all the objects.
	 *
	 * @param unknown_type $func_name
	 * @param unknown_type $values
	 * @return unknown
	 */
	public function __call($func_name, $values) {

		if (strpos($func_name,"getarray_") === 0) {
			$column = substr($func_name, 9);
			return $this->getarray($column);
		} elseif (strpos($func_name,"setarray_") === 0) {
			$column = substr($func_name, 9);
			return $this->setarray($column, $values[0]);
		} elseif (count($this)==1) {
			$this[0]->__call($func_name, $values);
		}
		else
		{
			throw new TDBM_Exception("Method ".$func_name." not found");
		}

	}

	private function getarray($column) {
		$arr = array();
		foreach ($this as $object) {
			$arr[] = $object->__get($column);
		}
		return $arr;
	}

	private function setarray($column, $value) {
		foreach ($this as $object) {
			$object->__set($column, $value);
		}
	}


}

/**
 * Abstract class for the filters used in TDBM_Object::getObjects method.
 *
 */
abstract class DBM_AbstractFilter {
	protected $db_connection;

	public function DBM_AbstractFilter($db_connection=null) {
		$this->db_connection = $db_connection;
		if ($this->db_connection == null) {
			$this->db_connection = TDBM_Object::$main_db;
		}
	}

	/**
	 * Returns the SQL of the filter (the SQL WHERE clause).
	 *
	 */
	public abstract function toSql();

	/**
	 * Returns the tables used in the filter in an array.
	 *
	 */
	public abstract function getUsedTables();
}

class DBM_EqualFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_EqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			$str_data = ' IS NULL';
		} else {
			$str_data = "=".$this->db_connection->quoteSmart($this->data);
		}

		return $this->table_name.'.'.$this->column_name.$str_data;
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_DifferentFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_DifferentFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			$str_data = ' IS NOT NULL';
		} else {
			$str_data = "<>".$this->db_connection->quoteSmart($this->data);
		}

		return $this->table_name.'.'.$this->column_name.$str_data;
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_BetweenFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data1, $data2;

	public function DBM_BetweenFilter($table_name, $column_name, $data1, $data2, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data1 = $data1;
		$this->data2 = $data2;
	}

	public function toSql() {
		if ($this->data1 === null || $this->data2 === null) {
			throw new TDBM_Exception('Error in DBM_BetweenFilter: one of the value passed is NULL.');
		}

		return $this->table_name.'.'.$this->column_name.' BETWEEN '.$this->db_connection->quoteSmart($this->data1)." AND ".$this->db_connection->quoteSmart($this->data2);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_InFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data_array;

	public function DBM_InFilter($table_name, $column_name, $data_array, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data_array = $data_array;
	}

	public function toSql() {
		if (!is_array($this->data_array)) {
			$this->data_array = array($this->data_array);
		}

		$data_array_sql = array();

		foreach ($this->data_array as $data) {
			if ($data === null) {
				$data_array_sql[] = 'NULL';
			} else {
				$data_array_sql[] = $this->db_connection->quoteSmart($data);
			}
		}

		return $this->table_name.'.'.$this->column_name.' IN ('.implode(',',$data_array_sql).")";
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_LessFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_LessFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in DBM_LessFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name."<".$this->db_connection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_LessOrEqualFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_LessOrEqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in DBM_LessOrEqualFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name."<=".$this->db_connection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_GreaterFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_GreaterFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in DBM_GreaterFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name.">".$this->db_connection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_GreaterOrEqualFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_GreaterOrEqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in DBM_GreaterOrEqualFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name.">=".$this->db_connection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_LikeFilter extends DBM_AbstractFilter {
	private $table_name;
	private $column_name;
	private $data;

	public function DBM_LikeFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql() {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in DBM_LikeFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name." LIKE ".$this->db_connection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class DBM_NotFilter extends DBM_AbstractFilter {
	private $filter;

	public function DBM_NotFilter(DBM_AbstractFilter $filter, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->filter = $filter;
	}

	public function toSql() {
		return 'NOT ('.$this->filter->toSql().')';
	}

	public function getUsedTables() {
		return $this->filter->getUsedTables();
	}
}

class DBM_AndFilter extends DBM_AbstractFilter {
	private $filters_array;

	public function DBM_AndFilter($filters_array, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->filters_array = $filters_array;
	}

	public function toSql() {
		if (!is_array($this->filters_array)) {
			$this->filters_array = array($this->filters_array);
		}

		$filters_array_sql = array();

		foreach ($this->filters_array as $filter) {
			if (!is_a($filter, 'DBM_AbstractFilter')) {
				throw new TDBM_Exception("Error in DBM_AndFilter: One of the parameters is not a filter.");
			}

			$filters_array_sql[] = "(".$filter->toSql().")";
		}

		if (count($filters_array_sql)>0)
		return '('.implode(' AND ',$filters_array_sql).')';
		else
		return '';
	}

	public function getUsedTables() {
		$tables = array();
		foreach ($this->filters_array as $filter) {
			$tables = array_merge($tables,$filter->getUsedTables());
		}
		// Remove tables in double.
		$tables = array_flip(array_flip($tables));
		return $tables;
	}
}

class DBM_OrFilter extends DBM_AbstractFilter {
	private $filters_array;

	public function DBM_OrFilter($filters_array, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->filters_array = $filters_array;
	}

	public function toSql() {
		if (!is_array($this->filters_array)) {
			$this->filters_array = array($this->filters_array);
		}

		$filters_array_sql = array();

		foreach ($this->filters_array as $filter) {
			if (!is_a($filter, 'DBM_AbstractFilter')) {
				throw new TDBM_Exception("Error in DBM_OrFilter: One of the parameters is not a filter.");
			}

			$filters_array_sql[] = $filter->toSql();
		}

		if (count($filters_array_sql)>0)
		return '('.implode(' OR ',$filters_array_sql).')';
		else
		return '';
	}

	public function getUsedTables() {
		$tables = array();
		foreach ($this->filters_array as $filter) {
			$tables = array_merge($tables,$filter->getUsedTables());
		}
		// Remove tables in double.
		$tables = array_flip(array_flip($tables));
		return $tables;
	}
}

class DBM_SQLStringFilter extends DBM_AbstractFilter {
	private $sql_string;

	public function DBM_SQLStringFilter($sql_string, $db_connection=null) {
		$this->DBM_AbstractFilter($db_connection);
		$this->sql_string = $sql_string;
	}

	public function toSql() {
		return $this->sql_string;
	}

	public function getUsedTables() {
		// Let's parse the SQL string and find all xxx.yyy tokens not enclosed in quotes.

		// First, let's remove all the stuff in quotes:

		// Let's remove all the \' found
		$work_str = str_replace("\\'",'',$this->sql_string);
		// Now, let's split the string using '
		$work_table = explode("'", $work_str);

		if (count($work_table)==0)
		return '';

		// if we start with a ', let's remove the first text
		if (strstr($work_str,"'")===0)
		array_shift($work_table);
			
		if (count($work_table)==0)
		return '';

		// Now, let's take only the stuff outside the quotes.
		$work_str2 = '';

		$i=0;
		foreach ($work_table as $str_fragment) {
			if (($i % 2) == 0)
			$work_str2 .= $str_fragment.' ';
			$i++;
		}

		// Now, let's run a regexp to find all the strings matching the pattern xxx.yyy
		//preg_match_all('/(\w+)\.(?:\w+)/', $work_str2,$capture_result);
		preg_match_all('/([a-zA-Z_](?:[a-zA-Z0-9_]*))\.(?:[a-zA-Z_](?:[a-zA-Z0-9_]*))/', $work_str2,$capture_result);

		$tables_used = $capture_result[1];
		// remove doubles:
		$tables_used = array_flip(array_flip($tables_used));
		return $tables_used;
	}
}

class DBM_OrderByColumn {
	private $order_table;
	private $order_column;
	private $order;

	public function DBM_OrderByColumn($order_table, $order_column, $order='ASC') {
		$this->order_table = $order_table;
		$this->order_column = $order_column;
		$this->order = $order;
	}

	public function toSql() {
		return $this->order_table.'.'.$this->order_column.' '.$this->order;
	}

	/**
	 * Returns a list of statements in an array from the list of comman separated statements.
	 * For instance, "users.user_id, global_roles_role_name" will return 2 statements:
	 * "users.user_id" AND "global_roles_role_name"
	 *
	 */
	public function toSqlStatementsArray() {
		return array($this->order_table.'.'.$this->order_column.' '.$this->order);
	}

	public function getUsedTables() {
		return array($this->order_table);
	}
}

class DBM_OrderBySQLString {
	private $sql_string;

	public function DBM_OrderBySQLString($sql_string, $db_connection=null) {
		$this->sql_string = $sql_string;
	}

	public function toSql() {
		return $this->sql_string;
	}

	/**
	 * Returns a list of statements in an array from the list of comman separated statements.
	 * For instance, "users.user_id, global_roles_role_name" will return 2 statements:
	 * "users.user_id" AND "global_roles_role_name"
	 *
	 */
	public function toSqlStatementsArray() {
		// First, let's implode the SQL string from the commas
		$comma_array = explode(',', $this->sql_string);

		$comma_array_2 = array();

		$is_inside_quotes = false;
		$sentence = '';
		foreach ($comma_array as $phrase) {
			$result = -1;
			while (true) {
				$result = strrpos($phrase, "'", $result+1);
				if ($result===false) {
					if ($sentence!='')
					$sentence .= ',';
					$sentence .= $phrase;

					if ($is_inside_quotes) {
						break;
					} else {
						$comma_array_2[] = $sentence;
						$sentence = '';
						break;
					}
				}
				else
				{
					$valid_result = true;
					//echo '-'.$phrase{$result-1}.'-';
					if ($result>0 && $phrase{$result-1}=='\\') {
						$valid_result = false;
					}
					if ($valid_result)
					$is_inside_quotes = !$is_inside_quotes;
				}
			}

		}

		return $comma_array_2;
	}

	public function getUsedTables() {
		// Let's parse the SQL string and find all xxx.yyy tokens not enclosed in quotes.

		// First, let's remove all the stuff in quotes:

		// Let's remove all the \' found
		$work_str = str_replace("\\'",'',$this->sql_string);
		// Now, let's split the string using '
		$work_table = explode("'", $work_str);

		if (count($work_table)==0)
		return '';

		// if we start with a ', let's remove the first text
		if (strstr($work_str,"'")===0)
		array_shift($work_table);
			
		if (count($work_table)==0)
		return '';

		// Now, let's take only the stuff outside the quotes.
		$work_str2 = '';

		$i=0;
		foreach ($work_table as $str_fragment) {
			if (($i % 2) == 0)
			$work_str2 .= $str_fragment.' ';
			$i++;
		}

		// Now, let's run a regexp to find all the strings matching the pattern xxx.yyy
		//preg_match_all('/(\w+)\.(?:\w+)/', $work_str2,$capture_result);
		preg_match_all('/([a-zA-Z_](?:[a-zA-Z0-9_]*))\.(?:[a-zA-Z_](?:[a-zA-Z0-9_]*))/', $work_str2,$capture_result);

		$tables_used = $capture_result[1];
		// remove doubles:
		$tables_used = array_flip(array_flip($tables_used));
		return $tables_used;
	}
}

TDBM_Object::$script_start_up_time = microtime(true);
register_shutdown_function(array("TDBM_Object","completeSaveOnExit"));

?>