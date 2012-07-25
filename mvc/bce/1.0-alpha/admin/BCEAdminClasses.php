<?php
class BCEFormInstanceBean{
	
	/**
	 * @var DaoDescriptorBean
	 */
	public $daoData;
	
	/**
	 * @var BaseFieldDescriptorBean
	 */
	public $idFieldDescriptor;
	
	public $mainBeanTableName;
	public $descriptors;
	
	public $renderer;
	public $validationHandler;
	public $action;
	public $method;

	public $attributes;
}

class FieldDescriptorBean{
	public $type;
	public $name;
	public $renderer;
	public $formater;
	public $fieldName;
	public $label;
	public $validators;
	public $isPk = false;
	public $active = true;
	public $is_new = false;
	public $db_column;
}

class BaseFieldDescriptorBean extends FieldDescriptorBean{
	/**
	* @var BeanMethodHelper
	*/
	public $getter;
	
	/**
	 * @var BeanMethodHelper
	 */
	public $setter;
	
	public function __construct(){
		$this->type = 'base';
	}
}

class ForeignKeyFieldDescriptorBean extends BaseFieldDescriptorBean{
	
	public $daoName;
	public $dataMethod;
	public $linkedIdGetter;
	public $linkedLabelGetter;
	
	/**
	 * @var DaoDescriptorBean
	 */
	public $daoData;
	
	public function __construct(){
		$this->type = 'fk';
	}
	
}

class Many2ManyFieldDescriptorBean extends FieldDescriptorBean{
	
	public $mappingDaoName;
	/**
	 * @var DaoDescriptorBean
	 */
	public $mappingDaoData;
	public $beanValuesMethod;
	public $mappingIdGetter;
	public $mappingLeftKeySetter;
	public $mappingRightKeyGetter;
	public $mappingRightKeySetter;
	
	public $linkedDaoName;
	
	/**
	 * @var DaoDescriptorBean
	 */
	public $linkedDaoData;
	public $linkedIdGetter;
	public $linkedLabelGetter;
	public $dataMethod;
	
	public function __construct(){
		$this->type = 'm2m';
	}
	
}

class CustomFieldDescriptorBean{
	
	public function __construct(){
		$this->type = 'custom';
	}
	
}



class DaoDescriptorBean{
	public $beanTableName;
	public $beanClassFields;
	public $daoMethods;
}

class BeanMethodHelper{
	
	public $name;
	public $dbType;
	public $phpType;

	/**
	 * @var ForeignKeyDataBean
	 */
	public $fkData;
}

class BeanFieldHelper{
	
	/**
	 * @var BeanMethodHelper
	 */
	public $getter;

	/**
	 * @var BeanMethodHelper
	 */
	public $setter;
	
	/**
	 * @var BaseFieldDescriptorBean
	 */
	public $asDescriptor;
	
	public $columnName;
	public $isPk = false;
	
	public $type = 'base';
	
}

class ForeignKeyDataBean{
	
	public $refTable;
	public $refColumn;
	
}