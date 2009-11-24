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
 * You should implement this class when you work with a DataSource that is linked to a DB for example.
 *
 * @Component
 */
abstract class DynamicDataSource implements UpdatableDataSourceInterface, ParametrisedInterface, OrderableDataSourceInterface {
	
	protected $rows;
	
	protected $order_columns;
	protected $orders;
	
	protected $offset;
	protected $limit;
	
	protected $params;
	protected $previous_params = null;
	
	/**
	 * Sets the order column that will be used for this datasource.
	 *
	 * @Property
	 * @param string $order_column
	 */
	public function setOrderColumn($order_column) { 
		$this->order_columns[] = $order_column;
		$this->rows = null;
	}
	
	/**
	 * Sets the order that will be used for this datasource (can be ASC or DESC).
	 *
	 * @Property
	 * @OneOf("ASC","DESC")
	 * @param string $order
	 */
	public function setOrder($order) { 
		$this->orders[] = $order;
		$this->rows = null;
	}

	/**
	 * Sets the orders array. Previous array is overwritten.
	 *
	 */
	public function setOrderColumns($orders=array()){
		$this->orders = $orders;
	}
	
	/**
	 * Sets the order columns array. Previous array is overwritten.
	 *
	 */
	public function setOrders($order_columns=array()){
		$this->order_columns = $order_columns;
	}
	
	/**
	 * This function loads parameters into the DataSource.
	 *
	 * @param mixed $params parameters for the loading.
	 */
	public function setParams($params=array()) {
		// If the parameters have changed, let's purge!
		if ($this->previous_params != $params) {
		$this->rows = null;
		}
		$this->previous_params = $params;

		$this->params = $params;
	}

	/**
	 * Stores the OFFSET for the data source
	 *
	 * @param int $offset
	 */
	public function setOffset($offset) {
		$this->offset = $offset;
	}
	
	/**
	 * Stores the LIMIT for the data source
	 *
	 * @param int $limit
	 */
	public function setOffset($limit) {
		$this->limit = $limit;
	}
	
}

?>