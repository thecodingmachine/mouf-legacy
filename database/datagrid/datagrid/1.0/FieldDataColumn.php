<?php
/**
 * This class represents a column in a datagrid.
 * The data for this column is retrieved from the public property of a row in a datasource.
 * Please note that if you access the data using getters, you should use the GetterDataColumn class instead.
 *
 * @Component
 */
class FieldDataColumn extends AbstractDataColumnInterface {
	
	private $fieldName;
	
	public function __construct($fieldName=null, $title=null, $sortColumn=null, $width=null, $formatter=null) {
		parent::__construct($title, $sortColumn, $width, $formatter);
		$this->fieldName = $fieldName;
	}
	
	/**
	 * The field name that will be used to get data from the row.
	 * 
	 * For instance, if you can access the name of a user using $row->name, then "name" is the field name.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $fieldName
	 */
	public function setFieldName($fieldName) {
		$this->fieldName = $fieldName;
	}
	
	/**
	 * Returns the value that will be displayed for that column. 
	 *
	 * @param mixed $row The database row that comes from the datasource used by the grid.
	 * @return string
	 */
	public function getValue($row) {
		$fieldName = $this->fieldName;
		return $row->$fieldName;
	}
}
?>