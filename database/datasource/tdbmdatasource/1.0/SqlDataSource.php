<?php
/*
Copyright 2007 - THE CODING MACHINE - David Négrier

This file is part of "The Xaja Machine".

"The Xaja Machine" is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

"The Xaja Machine" is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with "The Xaja Machine"; if not, write to the Free Software
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
class SqlDataSource extends ArrayObject implements XajaDataSourceInterface, XajaUpdatableDataSourceInterface {
	
	private $sql;
	private $countSql;
	
	private $order_column;
	private $order;
	private $previous_params = null;
	private $global_count = null;
	
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
	}
	
	/**
	 * Sets the order column that will be used for this datasource.
	 *
	 * @Property
	 * @param string $order_column
	 */
	public function setOrderColumn($order_column) { 
		$this->order_column = $order_column;
		$this->purge();
	}
	
	/**
	 * Sets the order that will be used for this datasource (can be ASC or DESC).
	 *
	 * @Property
	 * @OneOf("ASC","DESC")
	 * @param string $order
	 */
	public function setOrder($order) { 
		$this->order = $order;
		$this->purge();
	}
	
	public function __construct($sql=null, $countSql=null) {
		$this->sql = $sql;
		$this->countSql =$countSql;

		parent::__construct(array());
	}
	
	/**
	 * This function loads data into the DataSource.
	 *
	 * @param mixed $params parameters for the loading.
	 * @param unknown_type $offset
	 * @param unknown_type $limit
	 */
	public function load($params=array(), $offset=null, $limit=null) {
		// If the parameters have changed, let's purge!
		if ($this->previous_params != $params) {
			$this->purge();
		}
		$this->previous_params = $params;

		list($sql, $sqlCount) = $this->fillParameters($params);
		
		if ($this->order_column != null) {
			$sql .= " ORDER BY ".$this->order_column;
			if ($this->order != null)
				$sql .= " ".$this->order;
			else
				$sql .= " ASC";
		}
		
		$objects = DBM_Object::getTransientObjectsFromSQL($sql, $offset, $limit);
		$i=$offset?$offset:0;
		foreach ($objects as $object) {
			$this[$i] = $object;
			$i++;
		}
		
		if ($offset==null && $limit==null) {
			$this->global_count = $i;
		}
	}
	
	/**
	 * Replaces all the parameters passed into the filter string and the order by string
	 *
	 * @param array $params
	 * @return array an array with 2 members: array($resolved_filter_str, $resolved_order_str);
	 */
	private function fillParameters($params) {
		$keys = array_keys($params);
		$values = array_values($params);
		$keys2 = array_map (create_function( '$a'  , 'return "{".$a."}";' ), $keys);
		$values2 = array_map (create_function( '$a'  , ' return x_plainstring_to_dbprotected($a);' ), $values);
		// Now that we have the filter string, let's locate the parameters (in the form {toto})
		$sql = str_replace($keys2, $values2, $this->sql);
		$countSql = str_replace($keys2, $values2, $this->countSql);
		
		return array($sql, $countSql);
	}
	
	/**
	 * Removes all the fetched content from the datasource.
	 *
	 */
	public function purge() {
		foreach ($this as $key=>$value) {
			unset($this[$key]);
		}
		$this->global_count = null;
	}
	
	
	public function getGlobalCount($params=array()) {
		list($sql, $sqlCount) = $this->fillParameters($params);
		
		if ($this->global_count == null) {
			$this->global_count = DBM_Object::getValueFromSQL($sqlCount);
		}
		return $this->global_count;
	}
}

?>