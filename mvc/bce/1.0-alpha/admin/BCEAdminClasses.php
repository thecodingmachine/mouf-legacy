<?php
/**
 * These classes are simple stringifyed representations of the BCE elements.
 * 
 * It has no other use than providing autocompletion, in building the objects 
 * that will be used by the administration interface of BCE.
 * 
 * @author Kevin
 *
 */
class BCEFormInstanceBean{
	
	/**
	 * @var DaoDescriptorBean
	 */
	public $daoData;
	
	/**
	 * @var BaseFieldDescriptorBean
	 */
	public $idFieldDescriptor;

	/**
	 * The name of the DB table related to the main bean
	 * @var string
	 */
	public $mainBeanTableName;
	
	/**
	 * The field descriptors of the Form
	 * @var array<BaseFieldDescriptorBean>
	 */
	public $descriptors;

	/**
	 * The Renderer for the Form
	 * @var BCERendererInterface
	 */
	public $renderer;
	
	/**
	 * The validation handler 
	 * @var JsValidationHandlerInterface
	 */
	public $validationHandler;
	
	/**
	 * The action attribute of the form
	 * @var string
	 */
	public $action;
	
	/**
	 * The method attribute of the form
	 * @var string
	 */
	public $method;

	/**
	 * All the others attributes of the form
	 * @var array<string, string>
	 */
	public $attributes;
}

class FieldDescriptorBean{
	
	/**
	 * The type of the Descriptor. It can be on of "base", "fk", "m2m" or "custom"
	 * @var string
	 */
	public $type;
	
	/**
	 * The name of the descriptor instance
	 * @var string
	 */
	public $name;
	
	/**
	 * The name of the renderer instance of the descriptor
	 * @var string
	 */
	public $renderer;
	
	/**
	 * The name of the formatter instance of the descriptor
	 * @var string
	 */
	public $formatter;
	
	/**
	 * The name of the field, will be used as a unique key in the 
	 * Form since it will also be the "name" attribute of the generated fields.
	 * @var string
	 */
	public $fieldName;
	
	/**
	 * The Label of the field
	 * @var unknown_type
	 */
	public $label;
	
	/**
	 * The instance names of the validators of the descriptor
	 * @var array<string>
	 */
	public $validators;
	
	/**
	 * If the descriptor is pointing to a primary key field or not
	 * @var boolean
	 */
	public $isPk = false;
	
	/**
	 * If the descriptor should be added to the form or not
	 * @var boolean
	 */
	public $active = true;
	
	/**
	 * If the descriptor is a new one or not (means that it has been detected from an unimplemented getter
	 * @var boolean
	 */
	public $is_new = false;
	
	/**
	 * The name of the column handled by the decsriptor  
	 * @var string
	 */
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
	
	/**
	 * The name of the "foreign key" linked DAO
	 * @var string
	 */
	public $daoName;
	
	/**
	 * The method of the dao that will retrieve the list of available values
	 * @var string
	 */
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