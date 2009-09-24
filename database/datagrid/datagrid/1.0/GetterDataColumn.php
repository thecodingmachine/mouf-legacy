<?php
/**
 * This class represents a column in a datagrid.
 * The data for this column is retrieved using a getter on the datasource's row.
 * Please note that if you access the data using public properties, you should use the FieldDataColumn class instead.
 *
 * @Component
 */
class GetterDataColumn extends AbstractDataColumnInterface {
	
	private $getterName;
	
	/**
	 * The name of the getter method that will be used to get data from the row.
	 * 
	 * For instance, if you can access the name of a user using $row->getName(), then "getName" is the getter name.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $getterName
	 */
	public function setGetterName($getterName) {
		$this->getterName = $getterName;
	}
	
	/**
	 * Returns the value that will be displayed for that column. 
	 *
	 * @param mixed $row The database row that comes from the datasource used by the grid.
	 * @return string
	 */
	public function getValue($row) {
		$getterName = $this->getterName;
		return $row->$getterName();
	}
}
?>