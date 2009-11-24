<?php

/**
 * An interface describing a typical column from a datasource.
 *
 */
interface DataSourceColumnInterface {
	
	/**
	 * Returns the name of the column.
	 *
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the type of the column.
	 *
	 * @return string
	 */
	public function getType();
	
	/**
	 * Returns the value associated to the row passed in parameter for this column.
	 *
	 * @param array<object> $row
	 * @return string
	 */
	public function getValue($row);
}
?>