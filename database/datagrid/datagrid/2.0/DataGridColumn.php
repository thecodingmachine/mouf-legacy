<?php

/**
 * This class implements basic features for a data grid column.
 *
 * @Component
 */
class DataGridColumn implements DataGridColumnInterface {
	
	private $dataSourceColumn;
	private $title;
	private $sortColumn;
	private $width;
	private $formatters;
	private $resizable;
	private $textAlign;
	
	public function __construct($dataSourceColumn=null, $title=null, $sortColumn=null, $width=null, $formatter=null, $resizable=true) {
		$this->dataSourceColumn = $dataSourceColumn;
		$this->title = $title;
		$this->sortColumn = $sortColumn;
		$this->width = $width;
		$this->formatter = $formatter;
		$this->resizable = true;
	}
	
	/**
	 * Returns the column to be used in the datasource.
	 *
	 * @return DataSourceColumnInterface
	 */
	public function getDataSourceColumn() {
		return $this->dataSourceColumn;
	}
	
	/**
	 * The column to be used in the datasource.
	 *
	 * @Property
	 * @Compulsory
	 * @param DataSourceColumnInterface $dataSourceColumn
	 */
	public function setDataSourceColumn(DataSourceColumnInterface $dataSourceColumn) {
		$this->dataSourceColumn = $dataSourceColumn;
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
	 * @return DataSourceColumnInterface
	 */
	public function getSortColumn() {
		return $this->sortColumn;
	}
	
	/**
	 * The "sort column" to be passed to the datasource when the user wants to sort on that column.
	 * If none is provided, the column is not sortable.
	 * 
	 * @Property
	 * @param DataSourceColumnInterface $sortColumn
	 */
	public function setSortColumn(DataSourceColumnInterface $sortColumn) {
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
	 * Sets the formatters used to display that column.
	 * If no formatter is specified, content is displayed as text.
	 *
	 * @Property
	 * @param array<FormatterInterface> $formatters
	 */
	public function setFormatters(array $formatters) {
		$this->formatters = $formatters;
	}
	
	/**
	 * Returns the formatter for this column.
	 *
	 * @return array<FormatterInterface>
	 */
	public function getFormatters() {
		return $this->formatters;
	}
	
	/**
	 * Whether the column can be resized or not.
	 *
	 * @Property
	 * @param bool $resizable
	 */
	public function setResizable($resizable) {
		$this->resizable = $resizable;
	}
	
	/**
	 * Returns whether the column can be resized or not.
	 *
	 * @return bool
	 */
	public function isResizable() {
		return $this->resizable;
	}

	
	/**
	 * The way to align the text in the grid.
	 *
	 * @Property
	 * @OneOf("left", "center", "right")
	 * @OneOfText("Left", "Center", "Right")
	 * @param string $textAlign
	 */
	public function setTextAlign($textAlign) {
		$this->textAlign = $textAlign;
	}
	
	/**
	 * Returns the alignment of the text.
	 *
	 * @return string
	 */
	public function getTextAlign() {
		return $this->textAlign;
	}
	
	/**
	 * Returns the value associated to the row passed in parameter for this column.
	 *
	 * @param array<object> $row
	 * @param int $rowid
	 * @return string
	 */
	public function getValue($row, $rowid) {
		$dsColumn = $this->getDataSourceColumn();
		if ($dsColumn == null) {
			error_log("Error: DataGridColumn has no DataSourceColumn associated.");
			throw new Exception("Error: DataGridColumn has no DataSourceColumn associated.");
		}
		$name = $dsColumn->getName();
		$value = $row->$name;
		
		if (is_array($this->formatters)) {
			foreach ($this->formatters as $formatter) {
				$value = $formatter->format($value);
			}
		}
		return $value;
	}
}
?>