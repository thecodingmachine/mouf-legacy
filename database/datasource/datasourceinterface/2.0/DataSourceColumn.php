<?php

/**
 * A typical column from a datasource.
 * It has a name and a type.
 *
 * @Component
 */
class DataSourceColumn {
	
	private $name;
	private $type;
	private $formatters = array();

	/**
	 * The name of the column.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the name of the column.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * The type of the column.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	
	/**
	 * Returns the type of the column.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * The formatters of the column.
	 *
	 * @Property
	 * @Compulsory
	 * @param array<FormatterInterface> $formatters
	 */
	public function setFormatters($formatters) {
		$this->formatters = $formatters;
	}
	
	
	/**
	 * Returns the formatters of the column.
	 *
	 * @return array<FormatterInterface>
	 */
	public function getFormatters() {
		return $this->formatters;
	}
	
	/**
	 * Returns the value associated to the row passed in parameter for this column.
	 *
	 * @param array<object> $row
	 * @return string
	 */
	public function getValue($row) {
		$name = $this->name;
		$value = $row->$name;
		foreach ($this->formatters as $formatter) {
			$value = $formatter->format($value);
		}
		return $value;
	}
}
?>