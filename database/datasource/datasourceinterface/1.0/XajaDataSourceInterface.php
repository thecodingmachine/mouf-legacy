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
 * A XajaDataSourceInterface is the interface describing XajaDataSource objects.
 * All objects implementing this interface contain a number of records.
 * Each record should be accessed in the array style from the datasource (using the $datasource[$nb] syntax).
 * So basically, when implementing XajaDataSourceInterface, you should also implement ArrayAccess,IteratorAggregate,Countable to
 * provide the array behaviour.
 * The easiest way to do this is to extend the ArrayObject object.
 * 
 * Well... actually.... hum.... a XajaDataSourceInterface IS an object implementing the Arrays behaviour.
 * So there is nothing in it!
 */
interface XajaDataSourceInterface extends ArrayAccess,IteratorAggregate,Countable {
	
	
}

/**
 * The XajaUpdatableDataSourceInterface is implemented by any datasource that can be refreshed by a command.
 * Any object implementing this interface will provide a load method taking an array of parameters.
 *
 */
interface XajaUpdatableDataSourceInterface {
	/**
	 * This function loads data into the DataSource.
	 *
	 * @param mixed $params parameters for the loading.
	 * @param integer $offset
	 * @param integer $limit
	 */
	public function load($params=array(), $offset=null, $limit=null);
	
	/**
	 * Returns the global size of the source (not the size retrieved up to now).
	 * @return integer
	 */
	public function getGlobalCount($params=array());
	
}

/**
 * Objects implementing XajaReverseDataSourceInterface provides ways to update themselves automatically.
 * A callback can be registered using the onUpdateCallback method to provide some behaviour when the DataSource is updated.
 *
 */
interface XajaReverseDataSourceInterface {
	/**
	 * Registers a callback method that will be called when some data is updated in the datasource.
	 *
	 * @param callback $callback
	 */
	public function registerUpdateCallback($callback);
}

?>