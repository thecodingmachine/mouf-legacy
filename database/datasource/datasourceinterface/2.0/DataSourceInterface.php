<?php
/*
Copyright 2007 - THE CODING MACHINE - David Négrier

This file is part of "The  Machine".

"The  Machine" is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

"The Mouf Machine" is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with "The  Machine"; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define ("DS_FETCH_ASSOC",1);
define ("DS_FETCH_OBJ",2);
/**
 * A DataSourceInterface is the interface describing DataSource objects.
 * All objects implementing this interface contain a number of records.
 * 
 * 
 */
interface DataSourceInterface {
	
	/**
	 * Returns the column that acts as a key of the Data Source
	 * @return DataSourceColumnInterface
	 */
	public function getKeyColumn();
	
	/**
	 * Returns the column list of a Data Source
	 * @return array<DataSourceColumnInterface>
	 */
	public function getColumns();
	
	/**
	 * Returns a specific column of a Data Source by its name
	 *
	 * @param string $name
	 * @return DataSourceColumnInterface
	 */
	public function getColumn($name);
	
	/**
	 * Returns a specific row determined by its key. Return type depends on mode (DS_FETCH_ASSOC or DS_FETCH_OBJ)
	 *
	 * @param string $key
	 * @param int $mode
	 * @return array|object
	 */
	public function getRowByKey($key, $mode=DS_FETCH_OBJ);
	
	/**
	 * Returns all rows of the DataSource. Return type depends on mode (DS_FETCH_ASSOC or DS_FETCH_OBJ)
	 *
	 * @param int $mode
	 * @return array<array>|array<object>
	 */
	public function getRows($mode = DS_FETCH_OBJ);
	
	/**
	 * Returns the count of the Data Source's rows contained (independent of any limit or offset set.
	 * @return int
	 */
	public function getRowCount();
}

/**
 * Objects implementing ReverseDataSourceInterface provides ways to update themselves automatically.
 * A callback can be registered using the onUpdateCallback method to provide some behaviour when the DataSource is updated.
 *
 */
interface ReverseDataSourceInterface extends DataSourceInterface {
	/**
	 * Registers a callback method that will be called when some data is updated in the datasource.
	 *
	 * @param callback $callback
	 */
	public function registerUpdateCallback($callback);
}

interface ParametrisedInterface extends DataSourceInterface {
	
	/**
	 * Adds a parameter to the Data Source
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setParameter($key,$value) ;
	
	/**
	 * Returns the set of parameters applied to the Data Source
	 * @return array
	 */
	public function getParameters();
	
	/**
	 * Returns the value of the parameter defined by $key
	 * @return string
	 */
	public function getParameter($key);
}

?>