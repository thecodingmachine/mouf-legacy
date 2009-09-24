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
 * The XajaRecord object is a soft object (a bit like Javascript objects).
 * You can get and set any properties from a record object and it will be stored.
 */
class XajaRecord {
	
	private $_members;
	
	/**
	 * A XajaRecord can be created from an array or from another XajaRecord.
	 *
	 * @param mixed $array
	 */
	public function __construct($model = null) {
		if ($model instanceof XajaRecord) {
			$this->_members = $model->_members;
		} elseif (is_array($model) || $model instanceof IteratorAggregate) {
			foreach ($model as $key => $value) {
				$this->_members[$key] = $value;
			}
		}
	}
	
	public function __get($key) {
		return $this->_members[$key];
	}
	
	public function __set($key, $value) {
		$this->_members[$key] = $value;
	}
	
	public function toArray() {
		return $this->_members;
	}
	
}

?>