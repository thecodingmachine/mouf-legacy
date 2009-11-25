<?php

/**
 * This interface represents a column in a data grid.
 *
 */
interface DataGridColumnInterface {

	/**
	 * Returns the column to sort on in the bound datasource.
	 *
	 * @return DataSourceColumnInterface
	 */
	public function getDataSourceColumn();
	
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
	 * @return DataSourceColumnInterface
	 */
	public function getSortColumn();
	
	/**
	 * Returns the width of the column (in pixels).
	 *
	 * @return int
	 */
	public function getWidth();
	
	/**
	 * Returns true if the column is resizable.
	 *
	 * @return bool
	 */
	public function isResizable();
	
	/**
	 * Returns the alignment of the text.
	 *
	 * @return string
	 */
	public function getTextAlign();
		
}
?>