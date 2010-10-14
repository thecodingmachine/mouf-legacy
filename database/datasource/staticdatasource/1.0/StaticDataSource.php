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
 * @Component
 */
class StaticDataSource extends ArrayObject implements XajaDataSourceInterface, OrderableDataSourceInterface {

	private $orderColumn;
	private $order;
	
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
		$this->sort();
	}
	
	/**
	 * Adds a new record to the datasource.
	 * The record is taken as an array and transformed in a XajaRecord object.
	 *
	 * @param array $array
	 */
	public function addRecordFromArray(array $array) {
		$this[] = new XajaRecord($array);
		$this->sort();
	}
	
	public function toJSON() {
		$array = array();
		foreach ($this as $record) {
			$array[] = $record->toArray();
		}
		return json_encode($array);
	}
	
	/**
	 * Sets the order column that will be used for this datasource.
	 *
	 * @Property
	 * @param string $order_column
	 */
	public function setOrderColumn($order_column) {
		$this->orderColumn = $order_column;
		$this->sort();
	}
	
	/**
	 * Sets the order that will be used for this datasource (can be ASC or DESC).
	 *
	 * @Property
	 * @OneOf("ASC","DESC")
	 * @param string $order
	 */
	public function setOrder($order) {
		$this->order = strtoupper($order);
		$this->sort();
	}
	
	private function sort() {
		if ($this->orderColumn == null)
			return;
			
		$array = $this->getArrayCopy();
		usort($array, array($this,"compareXajaRecords"));
		$this->exchangeArray($array);
	}
	
	public function compareXajaRecords($x1, $x2) {
		$order = strtoupper($this->order);
		if ($order == null)
			$order = "ASC";

		$orderColumn = $this->orderColumn;
		if (is_numeric($x1->$orderColumn) && is_numeric($x2->$orderColumn)) {
			if ($order == "ASC") {
				return $x1->$orderColumn - $x2->$orderColumn;
			} else {
				return $x2->$orderColumn - $x1->$orderColumn;
			}
		} else {
			if ($order == "ASC") {
				return strcmp($x1->$orderColumn, $x2->$orderColumn);
			} else {
				return strcmp($x2->$orderColumn, $x1->$orderColumn);
			}
		}
	}
}

?>