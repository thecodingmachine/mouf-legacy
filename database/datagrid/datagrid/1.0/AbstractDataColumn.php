<?php

/**
 * This class implements basic features for a data column, but does not provide the way to retrieve data from the DB row.
 *
 */
abstract class AbstractDataColumnInterface implements DataColumnInterface {
	
	private $title;
	private $sortColumn;
	private $width;
	private $formatter;
	
	public function __construct($title=null, $sortColumn=null, $width=null, $formatter=null) {
		$this->title = $title;
		$this->sortColumn = $sortColumn;
		$this->width = $width;
		$this->formatter = $formatter;
	}
	
	/**
	 * Returns the title for the column
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * The title for the column
	 *
	 * @Property
	 * @Compulsory
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Returns true if the column should be sortable, false otherwise.
	 *
	 * @return bool
	 */
	public function isSortable() {
		return !empty($this->sortColumn);
	}
	
	/**
	 * Returns the "sort column" to be passed to the datasource when the user wants to
	 * sort on that column.
	 *
	 * @return string
	 */
	public function getSortColumn() {
		return $this->sortColumn;
	}
	
	/**
	 * The "sort column" to be passed to the datasource when the user wants to sort on that column.
	 * If none is provided, the column is not sortable.
	 * 
	 * @Property
	 * @param string $sortColumn
	 */
	public function setSortColumn($sortColumn) {
		$this->sortColumn = $sortColumn;
	}
	
	/**
	 * Returns the width of the column (in pixels).
	 *
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * The width of the column (in pixels).
	 *
	 * @Property
	 * @Compulsory
	 * @param int $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}
	
	/**
	 * Sets the formatter used to display that column.
	 * If no formatter is specified, content is displayed as text.
	 *
	 * @param DataColumnFormatterInterface $formatter
	 */
	public function setFormatter(DataColumnFormatterInterface $formatter) {
		$this->formatter = $formatter;
	}
	
	/**
	 * Returns the formatter for this column.
	 *
	 * @return DataColumnFormatterInterface
	 */
	public function getFormatter() {
		return $this->formatter;
	}

}
?>