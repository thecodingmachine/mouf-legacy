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
	 * @var DB_ConnectionInterface
	 */
	private $dbConnection;
	
	/**
	 * The SQL that will be run to load data in the datasource.
	 * This SQL can contain parameters (for instance {user_id}), but should not contain
	 * any ORDER BY and OFFSET/LIMIT keywords (since the datasource will
	 * add those).
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
	 * This function returns data in rows.
	 *
	 * @param mixed $params parameters for the loading.
	 * @param unknown_type $offset
	 * @param unknown_type $limit
	 */
	public function getRows() {
		

		if(!$this->rows) {
		
		$this->fillParameters();
		
		if ($this->order_column != null) {
			$sql .= " ORDER BY ".$this->order_column;
			if ($this->order != null)
				$sql .= " ".$this->order;
			else
				$sql .= " ASC";
		}
		
		$objects = DBM_Object::getTransientObjectsFromSQL($sql, $offset, $limit);
		$i=$offset?$offset:0;
		// TODO: la ligne suivante est fausse mais je sais pas pourquoi...
		$key_column = $this->getKeyColumn();
		foreach ($objects as $object) {
			$this->rows[$object->$key_column] = $object;
			$i++;
		}
		
		if ($offset==null && $limit==null) {
			$this->global_count = $i;
		}
		
		}
		
		return $this->rows;
	}
	
	/**
	 * Replaces all the parameters passed into the filter string and the order by string
	 *
	 * @param array $params
	 * @return array an array with 2 members: array($resolved_filter_str, $resolved_order_str);
	 */
	private function fillParameters() {
		$params = $this->params;
		$keys = array_keys($params);
		$values = array_values($params);
		$keys2 = array_map (create_function( '$a'  , 'return "{".$a."}";' ), $keys);
		$values2 = array_map (create_function( '$a'  , ' return plainstring_to_dbprotected($a);' ), $values);
		// Now that we have the filter string, let's locate the parameters (in the form {toto})
		$this->sql = str_replace($keys2, $values2, $this->sql);
		$this->countSql = str_replace($keys2, $values2, $this->countSql);
		
	}

	public function getGlobalCount($params=array()) {
		list($sql, $sqlCount) = $this->fillParameters($params);
		
		if ($this->global_count == null) {
			$this->global_count = DBM_Object::getValueFromSQL($sqlCount);
		}
		return $this->global_count;
	}
	
	private function getOrderStatement() {
		$order_statement = " ";
		$order_array = $this->orders;
		$order_column_array = $this->order_columns;
		
		if(count($order_array)!=count($order_column_array)) {
			throw new DatasourceException("Order Columns array and Order Types array don't have the same length!",null);
		}
		
		if($order_column_array && is_array($order_column_array)) {
			$count_column_array = count($order_column_array);
			$order_statement = "ORDER BY ";
			$i=0;
			foreach ($order_column_array as $order_column) {
				$order_statement .= " $order_column".$order_array[$i]." ";
				$i++;
				if($i+1<$count_column_array) $order_statement.= ", ";
			}
		}
		return $order_statement;
	}
	
	
}

?>