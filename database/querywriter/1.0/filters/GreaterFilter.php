<?php
/*
 Copyright (C) 2006-2011 David Négrier - THE CODING MACHINE

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace database\querywriter\filters;

/**
 * The GreaterFilter class translates into an ">" SQL statement.
 * 
 * @Component
 * @author David Négrier
 */
class GreaterFilter implements FilterInterface {
	private $tableName;
	private $columnName;
	private $value;
	
	/**
	 * The table name (or alias if any) to use in the filter.
	 * 
	 * @Property
	 * @Compulsory
	 * @param string $tableName
	 */
	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * The column name (or alias if any) to use in the filter.
	 * 
	 * @Property
	 * @Compulsory
	 * @param string $columnName
	 */
	public function setColumnName($columnName) {
		$this->columnName = $columnName;
	}

	/**
	 * The value to compare to in the filter.
	 * 
	 * @Property
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	private $enableCondition;
	
	/**
	 * You can use an object implementing the ConditionInterface to activate this filter conditionnally.
	 * If you do not specify any condition, the filter will always be used.
	 *
	 * @param ConditionInterface $enableCondition
	 */
	public function setEnableCondition($enableCondition) {
		$this->enableCondition = $enableCondition;
	}
	
	/**
	 * Default constructor to build the filter.
	 * All parameters are optional and can later be set using the setters.
	 * 
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $value
	 */
	public function GreaterFilter($tableName=null, $columnName=null, $value=null) {
		$this->tableName = $tableName;
		$this->columnName = $columnName;
		$this->value = $value;
	}

	/**
	 * Returns the SQL of the filter (the SQL WHERE clause).
	 *
	 * @param \DB_ConnectionInterface $dbConnection
	 * @return string
	 */
	public function toSql(\DB_ConnectionInterface $dbConnection) {
		if ($this->enableCondition != null && !$this->enableCondition->isOk()) {
			return "";
		}
		

		if ($this->value === null) {
			throw new Exception("Error in GreaterFilter: trying to compare $this->tableName.$this->columnName with NULL.");
		}

		return $this->tableName.'.'.$this->columnName.">".$dbConnection->quoteSmart($this->value);
	}

	/**
	 * Returns the tables used in the filter in an array.
	 *
	 * @return array<string>
	 */
	public function getUsedTables() {
		if ($this->enableCondition != null && !$this->enableCondition->isOk()) {
			return array();
		}
		return array($this->tableName);
	}
}
