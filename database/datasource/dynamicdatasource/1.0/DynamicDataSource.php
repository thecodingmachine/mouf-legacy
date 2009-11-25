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
 * A DynamicDataSource is an abstract class that contains parameters you need to work with a dynamic data source.
 * Any change on a parameter deletes the stored rows
 * 
 * You should implement this class when you work with a DataSource that is linked to a DB for example.
 *
 * @Component
 */
abstract class DynamicDataSource implements ParametrisedInterface, OrderableDataSourceInterface {
	
	/**
	 * The data, as an array of array.
	 * The key is the keyColumn.
	 *
	 * @var array<string, array>
	 */
	protected $rows;
	
	/**
	 * @var DataSourceColumnInterface
	 */
	protected $keyColumn;
	
	/**
	 * @var array<DataSourceColumnInterface>
	 */
	protected $columns;
	
	/**
	 * @var array<DataSourceColumnInterface>
	 */
	protected $order_columns;
	
	/**
	 * @var array<string>
	 */
	protected $orders;
	
	/**
	 * @var int
	 */
	protected $offset;

	/**
	 * @var int
	 */
	protected $limit;
	
	/**
	 * @var array<string, string>
	 */
	protected $params;
	
	/**
	 * Returns the column that acts as a key of the Data Source.
	 * 
	 * @return DataSourceColumnInterface
	 */
	public function getKeyColumn() {
		return $this->keyColumn;
	}
	
	/**
	 * Sets the key column.
	 *
	 * @Property
	 * @Compulsory
	 * @param DataSourceColumnInterface $keyColumn
	 */
	public function setKeyColumn(DataSourceColumnInterface $keyColumn) {
		$this->keyColumn = $keyColumn;
	}
	
	/**
	 * Returns the columns of the Data Source.
	 * 
	 * @return DataSourceColumnInterface
	 */
	public function getColumns() {
		return $this->columns;
	}
	
	/**
	 * Sets the columns.
	 *
	 * @Property
	 * @Compulsory
	 * @param array<DataSourceColumnInterface> $columns
	 */
	public function setColumns(array $columns) {
		$this->columns = $columns;
	}
	
	/**
	 * Returns a specific column of a Data Source by its name
	 *
	 * @param string $name
	 * @return DataSourceColumnInterface
	 */
	public function getColumn($name) {
		foreach ($this->columns as $column) {
			if ($column->getName() == $name) {
				return $column;
			}
		}
		return null;
	}
	
	/**
	 * Adds an order column that will be used for this datasource.
	 * The first column to be added will be the first sort column, the second will be the second sort column, etc...
	 *
	 * @param DataSourceColumnInterface $order_column
	 * @param string $order One of ASC or DESC.
	 */
	public function addOrderColumn(DataSourceColumnInterface $order_column, $order) { 
		$this->order_columns[] = $order_column;
		$this->orders[] = $order;
		$this->rows = null;
	}
	
	/**
	 * Sets the orders array. Previous array is overwritten.
	 *
	 * @Property
	 * @param array<DataSourceColumnInterface> $columns
	 */
	public function setOrderColumns(array $columns=array()){
		$this->order_columns = $columns;
		$this->rows = null;
	}
	
	/**
	 * Sets the order columns array. Previous array is overwritten.
	 *
	 * @Property
	 * @param array<string> $orders
	 */
	public function setOrders($orders=array()){
		$this->orders = $orders;
		$this->rows = null;
	}
	
	/**
	 * Returns all Data Source' order columns array
	 * @return array<DataSourceColumnInterface>
	 */
	public function getOrderColumns() {
		return $this->order_columns;
	}
	
	/**
	 * Returns all Data Source' orders array
	 * @return array<string>
	 */
	public function getOrders() {
		return $this->orders;
	}
	
	/**
	 * This function loads parameters into the DataSource.
	 *
	 * @Property
	 * @param array<string, string> $params parameters to be loaded.
	 */
	public function setParameters($params=array()) {
		// If the parameters have changed, let's purge!
		if ($this->params != $params) {
			$this->rows = null;
		}
		$this->params = $params;
	}
	
	/**
	 * Adds a parameter to the Data Source
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setParameter($key,$value) {
		$this->params[$key] = $value;
	}
	
	/**
	 * Returns the set of parameters applied to the Data Source
	 * @return array
	 */
	public function getParameters() {
		return $this->params;
	}
	
	/**
	 * Returns the value of the parameter defined by $key
	 * @return string
	 */
	public function getParameter($key) {
		return $this->params[$key];
	}

	/**
	 * Stores the OFFSET for the data source
	 *
	 * @param int $offset
	 */
	public function setOffset($offset) {
		$this->offset = $offset;
		$this->rows = null;
	}
	
	/**
	 * Stores the LIMIT for the data source
	 *
	 * @param int $limit
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
		$this->rows = null;
	}
	
}

?>