<?php

/**
 * A typical column from a datasource.
 * It has a name and a type.
 *
 * @Component
 */
class DataSourceDBColumn extends DataSourceColumn {
	
	private $dbColumn;
	
	/**
	 * The name of the column to map in the SQL dataset.
	 * If this parameter is not set, the name of the column will be used instead.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $column_name
	 */
	public function setDbColumn($column_name) {
		$this->dbColumn = $column_name;
	}
	
	/**
	 * Returns the name of the column.
	 *
	 * @return string
	 */
	public function getDbColumn() {
		if (empty($this->dbColumn)) {
			return $this->dbColumn;
		} else {
			return $this->getName();
		}
	}

	/**
	 * Returns the value associated to the row passed in parameter for this column.
	 *
	 * @param array<object> $row
	 * @return string
	 */
	public function getValue($row) {
		$name = $this->getDbColumn();
		$value = $row->$name;
		foreach ($this->formatters as $formatter) {
			$value = $formatter->format($value);
		}
		return $value;
	}
}
?>