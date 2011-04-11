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


/**
 * Interface for the filters used in TDBM_Service->getObjects method.
 *
 */
interface TDBM_FilterInterface {

	/**
	 * Returns the SQL of the filter (the SQL WHERE clause).
	 *
	 * @param DB_ConnectionInterface $dbConnection
	 * @return string
	 */
	public function toSql(DB_ConnectionInterface $dbConnection);

	/**
	 * Returns the tables used in the filter in an array.
	 *
	 */
	public function getUsedTables();
}

class TDBM_EqualFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_EqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			$str_data = ' IS NULL';
		} else {
			$str_data = "=".$dbConnection->quoteSmart($this->data);
		}

		return $this->table_name.'.'.$this->column_name.$str_data;
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_DifferentFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_DifferentFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			$str_data = ' IS NOT NULL';
		} else {
			$str_data = "<>".$dbConnection->quoteSmart($this->data);
		}

		return $this->table_name.'.'.$this->column_name.$str_data;
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_BetweenFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data1, $data2;

	public function TDBM_BetweenFilter($table_name, $column_name, $data1, $data2, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data1 = $data1;
		$this->data2 = $data2;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data1 === null || $this->data2 === null) {
			throw new TDBM_Exception('Error in TDBM_BetweenFilter: one of the value passed is NULL.');
		}

		return $this->table_name.'.'.$this->column_name.' BETWEEN '.$dbConnection->quoteSmart($this->data1)." AND ".$dbConnection->quoteSmart($this->data2);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_InFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data_array;

	public function TDBM_InFilter($table_name, $column_name, $data_array, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data_array = $data_array;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if (!is_array($this->data_array)) {
			$this->data_array = array($this->data_array);
		}

		$data_array_sql = array();

		foreach ($this->data_array as $data) {
			if ($data === null) {
				$data_array_sql[] = 'NULL';
			} else {
				$data_array_sql[] = $dbConnection->quoteSmart($data);
			}
		}

		return $this->table_name.'.'.$this->column_name.' IN ('.implode(',',$data_array_sql).")";
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_LessFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_LessFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in TDBM_LessFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name."<".$dbConnection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_LessOrEqualFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_LessOrEqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in TDBM_LessOrEqualFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name."<=".$dbConnection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_GreaterFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_GreaterFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in TDBM_GreaterFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name.">".$dbConnection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_GreaterOrEqualFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_GreaterOrEqualFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in TDBM_GreaterOrEqualFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name.">=".$dbConnection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_LikeFilter implements TDBM_FilterInterface {
	private $table_name;
	private $column_name;
	private $data;

	public function TDBM_LikeFilter($table_name, $column_name, $data, $db_connection=null) {
		$this->table_name = $table_name;
		$this->column_name = $column_name;
		$this->data = $data;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->data === null) {
			throw new TDBM_Exception("Error in TDBM_LikeFilter: trying to compare $this->table_name.$this->column_name with NULL.");
		}

		return $this->table_name.'.'.$this->column_name." LIKE ".$dbConnection->quoteSmart($this->data);
	}

	public function getUsedTables() {
		return array($this->table_name);
	}
}

class TDBM_NotFilter implements TDBM_FilterInterface {
	private $filter;

	public function TDBM_NotFilter(TDBM_FilterInterface $filter, $db_connection=null) {
		$this->filter = $filter;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		return 'NOT ('.$this->filter->toSql($dbConnection).')';
	}

	public function getUsedTables() {
		return $this->filter->getUsedTables();
	}
}

class TDBM_AndFilter implements TDBM_FilterInterface {
	private $filters_array;

	public function TDBM_AndFilter($filters_array, $db_connection=null) {
		$this->filters_array = $filters_array;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if (!is_array($this->filters_array)) {
			$this->filters_array = array($this->filters_array);
		}

		$filters_array_sql = array();

		foreach ($this->filters_array as $filter) {
			if (!$filter instanceof TDBM_FilterInterface) {
				throw new TDBM_Exception("Error in TDBM_AndFilter: One of the parameters is not a filter.");
			}

			$filters_array_sql[] = "(".$filter->toSql($dbConnection).")";
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

class TDBM_OrFilter implements TDBM_FilterInterface {
	private $filters_array;

	public function TDBM_OrFilter($filters_array, $db_connection=null) {
		$this->filters_array = $filters_array;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
		if (!is_array($this->filters_array)) {
			$this->filters_array = array($this->filters_array);
		}

		$filters_array_sql = array();

		foreach ($this->filters_array as $filter) {
			if (!$filter instanceof TDBM_FilterInterface) {
				throw new TDBM_Exception("Error in TDBM_OrFilter: One of the parameters is not a filter.");
			}

			$filters_array_sql[] = $filter->toSql($dbConnection);
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

class TDBM_SQLStringFilter implements TDBM_FilterInterface {
	private $sql_string;

	public function TDBM_SQLStringFilter($sql_string, $db_connection=null) {
		$this->sql_string = $sql_string;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
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

class TDBM_OrderByColumn {
	private $order_table;
	private $order_column;
	private $order;

	public function TDBM_OrderByColumn($order_table, $order_column, $order='ASC') {
		$this->order_table = $order_table;
		$this->order_column = $order_column;
		$this->order = $order;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
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

class TDBM_OrderBySQLString {
	private $sql_string;

	public function TDBM_OrderBySQLString($sql_string, $db_connection=null) {
		$this->sql_string = $sql_string;
	}

	public function toSql(DB_ConnectionInterface $dbConnection) {
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
?>