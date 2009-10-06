<?php
/**
 * This class generates automatically DAOs and Beans for TDBM.
 *
 */
class TDBMDaoGenerator {
	
	/**
	 * Writes the PHP bean file with all getters and setters from the table passed in parameter.
	 *
	 * @param string $fileName The path to the file that will be written
	 * @param string $className The name of the class
	 * @param string $tableName The name of the table
	 */
	public function generateBean($fileName, $className, $tableName) {
		$tableInfo = DB_Connection::$main_db->getTableInfoWithCache($tableName);
		
		$str = "<?php
/**
 * The $className class maps the '$tableName' table in database.
 *
 */
class $className extends DBM_Object 
{
";
		
		
		foreach ($tableInfo as $columnName => $metaData) {
			$str .= '	/**
	 * The getter for the "'.$columnName.'" column.
	 *
	 * @return string
	 */
	public function '.self::getGetterNameForPropertyName($columnName).'(){
		return $this->'.$columnName.';
	}
	
	/**
	 * The setter for the "'.$columnName.'" column.
	 *
	 * @param string $'.$columnName.'
	 */
	public function '.self::getSetterNameForPropertyName($columnName).'($'.$columnName.') {
		$this->'.$columnName.' = $'.$columnName.';
	}
';
		}
		
		$str .= "}
?>";
		
		file_put_contents($fileName ,$str);
	}

	/**
	 * Writes the PHP bean DAO with simple functions to create/get/save objects.
	 *
	 * @param string $fileName The path to the file that will be written
	 * @param string $className The name of the class
	 * @param string $tableName The name of the table
	 */
	public function generateDao($fileName, $beanFileName, $className, $beanClassName, $tableName) {
		$tableCamel = self::toCamelCase($tableName);
		
		$str = "<?php

require_once '$beanFileName';

/**
 * The $className class will maintain the persistance of $beanClassName class into the $tableName table.
 * 
 * @Component
 */
class $className 
{
	
	/**
	 * Return a new instance of $beanClassName object, that will be persisted in database.
	 *
	 * @return $beanClassName
	 */
	public function getNew$tableCamel() {
		return DBM_Object::getNewObject('$tableName', true, '$beanClassName');
	}
	
	/**
	 * Persist the $beanClassName instance
	 *
	 */
	public function save$tableCamel($beanClassName \$obj) {
		\$obj->save();
	}
	
	/**
	 * Get all $tableCamel records. 
	 *
	 * @return array<$beanClassName>
	 */
	public function get$tableCamel() {
		return DBM_Object::getObjects('$tableName', null, null, null, null, '$beanClassName');
	}
	
	/**
	 * Get VideoadsCampaignBean specified by its ID
	 *
	 * @param string \$id
	 * @return VideoadsCampaignBean
	 */
	public function get".$tableCamel."ById(\$id) {
		return DBM_Object::getObject('$tableName', \$id, '$beanClassName');
	}
}
?>";

		

		file_put_contents($fileName ,$str);
	}
	
	/**
	 * Transforms the property name in a setter name.
	 * For instance, phone => getPhone or name => getName
	 *
	 * @param string $methodName
	 * @return string
	 */
	public static function getSetterNameForPropertyName($propertyName) {
		$propName2 = self::toCamelCase($propertyName);
		return "set".$propName2;
	}
	
	/**
	 * Transforms the property name in a getter name.
	 * For instance, phone => getPhone or name => getName
	 *
	 * @param string $methodName
	 * @return string
	 */
	public static function getGetterNameForPropertyName($propertyName) {
		$propName2 = self::toCamelCase($propertyName);
		return "get".$propName2;
	}
	
	public static function toCamelCase($str) {
		$str = strtoupper(substr($str,0,1)).substr($str,1);
		while (true) {
			if (strpos($str, "_") === false)
				break;
				
			$pos = strpos($str, "_");
			$before = substr($str,0,$pos);
			$after = substr($str,$pos+1);
			$str = $before.strtoupper(substr($after,0,1)).substr($after,1);
		}
		return $str;
	}
}
?>