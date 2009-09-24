<?php

/**
 * This interface represents a column in a data grid.
 *
 */
interface DataColumnInterface {
	
	/**
	 * Returns the title for the column
	 *
	 * @return string
	 */
	public function getTitle();
	
	/**
	 * Returns true if the column should be sortable, false otherwise.
	 *
	 * @return bool
	 */
	public function isSortable();
	
	/**
	 * Returns the "sort column" to be passed to the datasource when the user wants to
	 * sort on that column.
	 *
	 * @return string
	 */
	public function getSortColumn();
	
	/**
	 * Returns the width of the column (in pixels).
	 *
	 * @return int
	 */
	public function getWidth();
	
	/**
	 * Returns the value that will be displayed for that column. 
	 *
	 * @param mixed $row The database row that comes from the datasource used by the grid.
	 * @return string
	 */
	public function getValue($row);
}
?>