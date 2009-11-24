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
}
?>