<?php
/*
Copyright 2007 - THE CODING MACHINE - Michael Durand

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
 * A DynamicDataSource is an abstract class that contains some of the most useful methods to work with a dynamic data source.
 * 
 * You should implement this class when you work with a DataSource that is linked to a DB.
 *
 * @Component
 */
abstract class DynamicDataSource extends ArrayObject implements UpdatableDataSourceInterface, ParametrisedInterface, OrderableDataSourceInterface {
	
	private $sql;
	private $countSql;
	
	private $order_column;
	private $order;
	private $previous_params = null;
	private $global_count = null;
	
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
		$values2 = array_map (create_function( '$a'  , ' return plainstring_to_dbprotected($a);' ), $values);
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