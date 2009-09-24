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
 * A DBMDataSource behaves like a standard array.
 *
 * @Component
 */
class DBMDataSource extends ArrayObject implements XajaDataSourceInterface, XajaUpdatableDataSourceInterface {
	
	private $table_name;
	private $filter_str;
	private $order_str;
	private $previous_params = null;
	private $hint_path;
	private $previous_offset;
	private $previous_limit;
	private $global_count = null;
	
	/**
	 * Sets the table that will be queried as a datasource.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $tableName
	 */
	public function setTableName($tableName) {
		$this->table_name = $tableName;
	}
	
	/**
	 * Sets the filter that will be applied to the datasource.
	 *
	 * @Property
	 * @param DBM_AbstractFilter $filter_bag
	 */
	public function setFilter($filter_bag) {
		$filter = DBM_Object::buildFilterFromFilterBag($filter_bag);
		$this->filter_str = $filter->toSql();
	}
	
	/**
	 * Sets the order that will be applied to the datasource.
	 *
	 * @Property
	 * @param array<DBM_OrderByColumn> $orderby_bag
	 */
	public function setOrder($orderby_bag) { 
		if (is_string($orderby_bag)) {
			$this->order_str = $orderby_bag;
		} elseif ($orderby_bag !== null) {
			$this->order_str = $orderby_bag->toSql();
		}
	}
	
	public function __construct($table_name, $filter_bag=null, $orderby_bag=null, $hint_path=null) {
		$this->table_name = $table_name;
		$this->hint_path = $hint_path;
		
		if ($filter_bag != null) {
			$filter = DBM_Object::buildFilterFromFilterBag($filter_bag);
			$this->filter_str = $filter->toSql();
		}
		/*if ($filter_bag instanceof DBM_AbstractFilter) {
			$this->filter_str = $filter_bag->toSql();
		} else if (is_string($filter_bag)) {
			$this->filter_str = $filter_bag;
		} else if ($filter_bag != null) {
			throw new Exception('Error, the parameter $filter_bag of the DBMDataSource\'s constuctor takes either a string or an object extending DBM_AbstractFilter in parameter.');
		}*/
		
		/*if ($orderby_bag != null) {
			$order_array = DBM_Object::buildOrderArrayFromOrderBag($orderby_bag);
			$this->order_str = $filter->toSql();
		}*/
		// TODO: this is far from being perfect. The orderby parameter is far from being
		// as efficient as the one of TDBM.
		if (is_string($orderby_bag)) {
			$this->order_str = $orderby_bag;
		} elseif ($orderby_bag !== null) {
			$this->order_str = $orderby_bag->toSql();
		}

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
			foreach ($this as $key=>$value) {
				unset($this[$key]);
			}
			$this->global_count = null;
		}
		$this->previous_params = $params;
		$this->previous_offset = $previous_offset;
		$this->previous_limit = $previous_limit;
		/*
		$keys = array_keys($params);
		$values = array_values($params);
		$keys2 = array_map (create_function( '$a'  , 'return "{".$a."}";' ), $keys);
		$values2 = array_map (create_function( '$a'  , ' return x_plainstring_to_dbprotected($a);' ), $values);
		// Now that we have the filter string, let's locate the parameters (in the form {toto})
		if ($this->filter_str != null)
			$resolved_filter_str = str_replace($keys2, $values2, $this->filter_str);
		if ($this->order_str != null)
			$resolved_order_str = str_replace($keys2, $values2, $this->order_str);
		*/
		list($resolved_filter_str, $resolved_order_str) = $this->fillParameters($params);
		
		$objects = DBM_Object::getObjects($this->table_name, $resolved_filter_str, $resolved_order_str, $offset, $limit, $this->hint_path);
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
		if ($this->filter_str != null)
			$resolved_filter_str = str_replace($keys2, $values2, $this->filter_str);
		if ($this->order_str != null)
			$resolved_order_str = str_replace($keys2, $values2, $this->order_str);
		
		return array($resolved_filter_str, $resolved_order_str);
	}
	
	/**
	 * Removes all the fetched content from the datasource.
	 *
	 */
	public function purge() {
		
	}
	
	
	public function getGlobalCount($params=array()) {
		list($resolved_filter_str, $resolved_order_str) = $this->fillParameters($params);
		
		if ($this->global_count == null) {
			$this->global_count = DBM_Object::getCount($this->table_name, $this->filter_str, $this->hint_path);
		}
		return $this->global_count;
	}
}

?>