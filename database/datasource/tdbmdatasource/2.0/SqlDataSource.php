<?php
/*
Copyright 2007 - THE CODING MACHINE - David Négrier

This file is part of "The  Machine".

"The  Machine" is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

"The  Machine" is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with "The  Machine"; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * A SqlDataSource is an object that contains the SQL to perform a request, and the behaves like the resultset.
 * This is an object that contains the results from a query.
 * The query is performed when the "load" function is called.
 * 
 * You should use this object when you need a DataSource to display objects in a grid, and when the data from this 
 * datasource comes from a SQL request.
 *
 * @Component
 */
class SqlDataSource extends DynamicDataSource {
	
	private $sql;
	private $countSql;
	private $global_count = null;
	
	/**
	 * Connection to DB
	 *
	 * @Property
	 * @Compulsory
	 * @var DB_ConnectionInterface
	 */
	public $dbConnection;
	
	/**
	 * The SQL that will be run to load data in the datasource.
	 * This SQL can contain parameters (for instance {user_id}), but should not contain
	 * any ORDER BY and OFFSET/LIMIT keywords (since the datasource will
	 * add those).
	 * This SQl query string MUST NOT finish by ";"
	 *
	 * @Property
	 * @Compulsory
	 * @param string $sql
	 */
	public function setSql($sql) {
		$this->sql = $sql;
		$this->rows = null;
	}
	
	/**
	 * The SQL that will return the total number of fields in the datasource.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $countSql
	 */
	public function setCountSql($countSql) {
		$this->countSql = $countSql;
		$this->rows = null;		
	}
	
	/**
	 * Sets the key column.
	 *
	 * @Property
	 * @Compulsory
	 * @param DataSourceDBColumn $keyColumn
	 */
	public function setKeyColumn(DataSourceColumnInterface $keyColumn) {
		$this->keyColumn = $keyColumn;
	}
	
	/**
	 * Sets the columns.
	 *
	 * @Property
	 * @Compulsory
	 * @param array<DataSourceDBColumn> $columns
	 */
	public function setColumns(array $columns) {
		$this->columns = $columns;
	}
	
	/**
	 * This function returns data in rows.
	 *
	 * @param int $mode
	 * @return array<array>|array<object>
	 */
	public function getRows($mode = DS_FETCH_OBJ) {		
		$this->queryIfNeeded();

		if ($mode == DS_FETCH_OBJ) {
			$rows = array();
			foreach ($this->rows as $key=>$row) {
				$rows[$key] = (object) $row;
			}
		} else {
			$rows = $this->rows;
		}
	
		return $rows;
	}
	
	/**
	 * Returns a specific row determined by its key. Return type depends on mode (DS_FETCH_ASSOC or DS_FETCH_OBJ)
	 *
	 * @param string $key
	 * @param int $mode
	 * @return array|object
	 */
	public function getRowByKey($key, $mode=DS_FETCH_OBJ) {
		
		$this->queryIfNeeded();
		
		if ($mode == DS_FETCH_OBJ) {
			return (object) $this->rows[$key];
		} else {
			return $this->rows[$key];
		}
	}
	
	private function queryIfNeeded() {
		if(!$this->rows) {
		
			$sql = $this->fillParameters($this->sql);
		
			$sql .= $this->getOrderStatement();
			
			$rows = $this->dbConnection->getAll($sql, PDO::FETCH_CLASS, "stdClass", $this->offset, $this->limit);

			$i=0;

			$this->rows = array();
			
			$key_column = $this->getKeyColumn();
			if ($key_column == null) {
				throw new DataSourceException("Error while querying database for an SqlDataSource: the SqlDataSource must have a keyColumn declared.");
			}
			if (empty($this->columns)) {
				throw new DataSourceException("Error while querying database for an SqlDataSource: the SqlDataSource must have at least one column declared.");
			}
			
			//$key_column_name = $key_column->getName();
			foreach ($rows as $object) {
				$id = $key_column->getValue($object);
				foreach ($this->columns as $column) {
					$this->rows[$id][$column->getName()] = $column->getValue($object);
				}
				//$this->rows[$object->$key_column_name] = $object;
				$i++;
			}
			
			if ($this->offset==null && $this->limit==null) {
				$this->global_count = $i;
			}
		
		}
	}
	
	/**
	 * Replaces all the parameters passed into the filter string and the order by string
	 *
	 * @param string $sql The Original SQL with parameters.
	 * @return string The SQL with parameters replaced.
	 */
	private function fillParameters($sql) {
		$params = $this->params;
		if ($params == null) {
			return $sql;
		}
		$keys = array_keys($params);
		$values = array_values($params);
		$keys2 = array_map (create_function( '$a'  , 'return "{".$a."}";' ), $keys);
		$values2 = array_map (create_function( '$a'  , ' return plainstring_to_dbprotected($a);' ), $values);
		// Now that we have the filter string, let's locate the parameters (in the form {toto})
		return str_replace($keys2, $values2, $sql);		
	}

	/**
	 * Returns the count of the Data Source's rows contained (independent of any limit or offset set.
	 * @return int
	 */
	public function getRowCount() {
		if ($this->global_count == null) {
			$countSql = self::fillParameters($this->countSql);
			
			$this->global_count = $this->dbConnection->getOne($countSql);
		}
		return $this->global_count;
	}
	
	/**
	 * Returns the "ORDER BY" part of the SQL to be applied. 
	 *
	 * @return string
	 */
	private function getOrderStatement() {
		$order_statement = " ";
		$order_array = $this->orders;
		$order_column_array = $this->order_columns;
		
		if(is_array($order_array) && is_array($order_column_array) && count($order_array)!=count($order_column_array)) {
			throw new DataSourceException("Order Columns array and Order Types array don't have the same length!",null);
		}
		
		if($order_column_array && is_array($order_column_array) && count($order_column_array)>0) {
			$count_column_array = count($order_column_array);
			$order_statement = " ORDER BY ";
			$i=0;
			foreach ($order_column_array as $order_column) {
				$order_statement .= $order_column->getDbColumn()." ".$order_array[$i]." ";
				$i++;
				if($i+1<$count_column_array) $order_statement.= ", ";
			}
		}
		return $order_statement;
	}
	
	
}

?>