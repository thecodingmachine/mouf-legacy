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

require_once('XajaRecord.php');

/**
 * A StaticDataSource behaves like a standard array.
 *
 */
class StaticDataSource extends ArrayObject implements XajaDataSourceInterface {
		
	public function __construct($array = null) {
		if ($array == null) {
			$array = array();
		}
		
		$records = array();
		foreach ($array as $item) {
			if (is_array($item)) {
				$records[] =  new XajaRecord($item);
			} else {
				$records[] = $item;
			}
		}
		
		parent::__construct($records);
	}
	
	/**
	 * Adds a new record to the datasource.
	 * The record is taken as an array and transformed in a XajaRecord object.
	 *
	 * @param unknown_type $array
	 */
	public function addRecordFromArray($array) {
		$this[] = new XajaRecord($array);
	}
	
	public function toJSON() {
		$array = array();
		foreach ($this as $record) {
			$array[] = $record->toArray();
		}
		return json_encode($array);
	}
}

?>