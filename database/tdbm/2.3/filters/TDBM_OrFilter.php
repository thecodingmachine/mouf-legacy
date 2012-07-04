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

/**
 * The TDBM_OrFilter class translates into an "Or" SQL statement between many filters.
 * 
 * @Component
 * @author David Négrier
 */
class TDBM_OrFilter implements TDBM_FilterInterface {
	private $filters;

	/**
	 * The filters that will be "OR"ed.
	 * 
	 * @Property
	 * @Compulsory
	 * @param array<TDBM_FilterInterface> $filter
	 */
	public function setFilters($filters) {
		$this->filter = $filter;
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
	 * @param array<TDBM_FilterInterface> $filter
	 */
	public function TDBM_OrFilter($filters=null) {
		$this->filters = $filters;
	}

	/**
	 * Returns the SQL of the filter (the SQL WHERE clause).
	 *
	 * @param DB_ConnectionInterface $dbConnection
	 * @return string
	 */
	public function toSql(DB_ConnectionInterface $dbConnection) {
		if ($this->enableCondition != null && !$this->enableCondition->isOk()) {
			return "";
		}
		

		if (!is_array($this->filters)) {
			$this->filters = array($this->filters);
		}

		$filters_sql = array();

		foreach ($this->filters as $filter) {
			if (!$filter instanceof TDBM_FilterInterface) {
				throw new TDBM_Exception("Error in TDBM_OrFilter: One of the parameters is not a filter.");
			}

			$filters_sql[] = "(".$filter->toSql($dbConnection).")";
		}

		if (count($filters_sql)>0) {
			return '('.implode(' OR ',$filters_sql).')';
		} else {
			return '';
		}
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
		$tables = array();
		foreach ($this->filters as $filter) {
			$tables = array_merge($tables,$filter->getUsedTables());
		}
		// Remove tables in double.
		$tables = array_flip(array_flip($tables));
		return $tables;
	}
}
