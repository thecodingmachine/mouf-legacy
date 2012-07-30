<?php
/**
 * ----------------------------------------------------------------
 * ------------------------- Head's UP!!!--------------------------
 * ----------------------------------------------------------------
 * 
 * This is one of the moste important file of the Form configuration. bce_utils is an AJAX helper that handles:
 *   - get data about an existing instance
 *   - get data about a DAO (can be used either for the main dao or any secondary dao used in FK, M2M, ... descriptors)
 *   
 *   Here are some explanations on how the form configuration works :
 *   - First, if the main dao isn't set, then the only choice is to select one in the dropdown list
 *   
 *   - Once the DAO is defined, bce_utils will be called to get instance data
 *   
 *   - Of course, the instance data will load the form configuration (id, name, class, validation handler, etc...,
 *     but also the descriptors. 
 *     Even more! bce_utils will also return a set of descriptors for each getter / setter of the main bean (the one handled by the main dao)
 *     
 *   - Finally, bce_utils also retuns DAO data, which are used when a secondary dao is required (FK or M2M daos for example)
 */

session_start();
require_once '../../../../../Mouf.php';
require_once(dirname(__FILE__)."/../../../../../mouf/reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../../../../../mouf/Moufspector.php");
require_once(dirname(__FILE__)."/../admin/BCEAdminClasses.php");

$query = $_GET['q'];
$inputName = $_GET['n'];

$utils = new BCEUtils();

//depending on the $query parameter, return instance or dao data
switch ($query) {
	case 'daoData':
		echo json_encode($utils->getDaoDataFromInstance($inputName));
	break;
	
	case 'instanceData':
		echo json_encode($utils->getInstanceData($inputName));
	break;
}

class BCEUtils{
	
	/**
	 * List of all objects that are used by the descriptors : 
	 *  - validators
	 *  - renderers
	 *  - formatters
	 *  - daos
	 */
	private $validators = array();
	private $renderers = array();
	private $formatters = array();
	private $daos = array();
	
	/**
	 * The @ApplyTo annotations in the validators, renderers and formatters 
	 * are used to define when to apply those instances to some descriptors.
	 * 
	 * For example, a DatePickerRenderer is applyied to Date, Timestamp, and Datetime PHP types
	 * 
	 * The $handleOrder variable defines the priority of the @ApplyTo annotations : 
	 * For example, 'pk' @ApplyTo annotation prevails on 'type' ones...
	 */
	public $handleOrder = array("pk", "type", "php", "db");
	
	public function __construct(){
		//Simply initialize collections (used for drop downs)
		$this->initValidators();
		$this->initRenderers();
		$this->initFormatters();
		$this->initDaos();
	}
	
	/**
	 * Get the list of all suitable DAOs, and put them into an associative array 
	 * Key is table name, which will help suggesting the right dao for a FK descriptor, see _fitDaoByTableName function
	 * @return array
	 */
	private function initDaos(){
		$daos = Moufspector::getComponentsList("DAOInterface");
		foreach ($daos as $className) {
			$descriptor = new MoufReflectionClass($className);
			$table = $descriptor->getAnnotations("dbTable");
			$table = $table[0];
			$daoForClass = MoufManager::getMoufManager()->findInstances($className);
			$daoForClass = $daoForClass[0];
			$this->daos[$table] = $daoForClass;
		}
	}
	
	/**
	 * Gets the list of instances of classes that implement the interface parameter.
	 * The returned array has 2 dimensions, first one is the type (see $handleOrder variable), and the second is the value of the type
	 * For example $array['php']['number']
	 * 
	 * This will help the configurator to suggest adapted validators, renderers, etc for new descriptors
	 * 
	 * @param string $interface
	 */
	private function initHandler($interface){
		$handlers = array();
		$instances = MoufManager::getMoufManager()->findInstances($interface);
		foreach ($instances as $instance) {
			$className = MoufManager::getMoufManager()->getInstanceType($instance);
			$classDesc = new MoufReflectionClass($className);
			$types = $classDesc->getAnnotations('ApplyTo');
			if (count($types)){
				$types = $types[0];
				$types = json_decode($types);
				foreach ($types as $criteria => $values) {
					foreach ($values as $value) {
						$handlers[$criteria][$value][] = $instance;
					}
				}
			}
		}
		return $handlers;
	}
	
	private function initValidators(){
		$this->validators = $this->initHandler('ValidatorInterface');
	}
	
	private function initRenderers(){
		$this->renderers = $this->initHandler('FieldRendererInterface');
	}
	
	private function initFormatters(){
		$this->formatters = $this->initHandler('FormatterInterface');
	}
	
	/**
	 * Shortcut for getting daoData from dao instancename, rather then from className
	 * @param string $daoInstanceName
	 */
	public function getDaoDataFromInstance($daoInstanceName){
		$desc = MoufManager::getMoufManager()->getInstanceDescriptor($daoInstanceName);
		$daoClass = $desc->getClassName();
		return $this->getDaoData($daoClass);
	}
	
	/**
	 * The DAO data will return the method descriptors for the dao itself and the bean that is handled by this dao.
	 * For example, 
	 *  - userDao has save, create, getById, etc.. methods
	 *  - userBean has getId, getName, getEmail, etc...
	 *  
	 *  The bean's methods can by used to suggest descriptors (the get / set Name methods) will suggest to create a nameDescriptor.
	 *  Moreover, those bean methods will be used for FK & M2M descriptors (like the linkedIdGetter property)
	 *  
	 *  The DAO's methods will be used for FK and M2M descriptors (like the dataMethod property)
	 * 
	 * @param string $daoClass
	 */
	private function getDaoData($daoClass){
		$daoDescripror = new DaoDescriptorBean();
		
		$class = new MoufReflectionClass($daoClass);
		$method = $class->getMethod("getById");
		$returnClass = $method->getAnnotations('return');
		
		list($fields, $table) = $this->getBeanMethods($returnClass[0]);
		$daoDescripror->beanClassFields = $fields;
		$daoDescripror->beanTableName = $table;
		$daoDescripror->daoMethods = $this->getDaoMethods($daoClass);
		
		return $daoDescripror;
	}
	
	/**
	 * Get the methods of a dao
	 * @param array<string> $daoClass
	 */
	private function getDaoMethods($daoClass){
		$daoMethodNames = array();
		$daoClassReflexion = new MoufReflectionClass($daoClass);
		$daoMethods = $daoClassReflexion->getMethods();
		foreach ($daoMethods as $method) {
			$daoMethodNames[] = $method->getName();
		}
		return $daoMethodNames;
	}
	
	/**
	 * Retrieve the methods of a bean class
	 * @param string $beanClassName
	 */
	private function getBeanMethods($beanClassName){
		$beanClass = new MoufReflectionClass($beanClassName);
		
		//The table name will be used to the DB model data as primary key or foreign keys
		$tableName = $beanClass->getAnnotations("dbTable");
		
		//Get parent class in order to distinguish the bean classe's methods from it's parents' ones
		$parentBeanClass = $beanClass->getParentClass()->getParentClass();
		$methods = $beanClass->getMethodsByPattern("^[gs]et");
		$methodsParent = $parentBeanClass->getMethodsByPattern("^[gs]et");
		$finalMethods = array();

		$connection = Mouf::getDbConnection();
		//Primary keys will be used to suggest the idFieldDescriptor
		$primaryKeys = $connection->getPrimaryKey($tableName[0]);
		
		foreach ($methods as $method) {
			/* @var $method  MoufReflectionMethod */
			if (!array_key_exists($method->getName(), $methodsParent)){//Only take the bean's methods, not the parent's ones
				
				$methodObj = new BeanMethodHelper();
				$methodObj->name = $method->getName();
				
				//Will help to suggest appropriate validators, formatters and rederers
				$returnAnnotation = $method->getAnnotations('dbType');
				$columnName = $method->getAnnotations('dbColumn');//Get column name to suggest descriptor name
				$columnName = $columnName[0];
				
				//If there is no column name, the method is not a getter or a setter, and therefore cannot be mapped to a decriptor
				if (!$columnName){
					continue;
				}
				
				$fieldIndex = self::toCamelCase($columnName, true)."Desc";
				
				/* This script has to get getter AND setter for each property,
				 * so the method descriptor is in fact linked to 2 methods : the getter and the setter */
				$fieldDataObj = isset($finalMethods[$fieldIndex]) ? $finalMethods[$fieldIndex] : new BeanFieldHelper();
				
				/* @var $fieldDataObj BeanFieldHelper */
				$fieldDataObj->columnName = $columnName;
				
				/* If the current column is the primary key of the table, the set the pk attribute which will 
				 * suggest teh descripor to be teh idFieldDescriptor
				 */
				foreach ($primaryKeys as $key){
					if ($key->name == $columnName){
						$fieldDataObj->isPk = true;
						break;
					}
				}
				 
				/*
				 * Like the primary key test, foreign keys will suggest the field to be a FK descriptor
				 * The fkData will tell which table is linked and so which dao should be the linked dao
				 * //TODO : linked column could be used to rather then regex match
				 */
				$referencedTables = $connection->getConstraintsOnTable($tableName[0], $columnName);
				if (!empty($referencedTables)){
					$ref = $referencedTables[0];
					$foreignKeyData = new ForeignKeyDataBean();
					$foreignKeyData->refTable = $ref['table2'];
					$foreignKeyData->refColumn = $ref['col2'];
					$methodObj->fkData = $foreignKeyData;
					$fieldDataObj->type = 'fk';
				}

				/* Set the type declared by the getter (db type is transleted into php type) */
				if (count($returnAnnotation)){
					$returnType = $returnAnnotation[0];
					$returnType = explode(" ", $returnType);
					$returnType = $returnType[0];
					$methodObj->dbType = $returnType;
					$phpType = $connection->getUnderlyingType($returnType);
					$methodObj->phpType = $phpType;
					$fieldDataObj->getter = $methodObj;
				}else{
					$fieldDataObj->setter = $methodObj;
				}
				$finalMethods[$fieldIndex] = $fieldDataObj;
			}
		}
		
		/* each  beanFieldHelper have to be converted to a FieldDeescriptorBean 
		 * in order to have same properties than the existing descriptors */
		foreach ($finalMethods as $columnName => $fieldData) {
			$fieldData->asDescriptor = $this->beanHelperConvert2Descriptor($fieldData);
		}
		
		return array($finalMethods, $tableName[0]);
	}
	
	/**
	 * Quite simple function that returns a FieldDecriptorBean from a BeanFieldHelper
	 * @param BeanFieldHelper $beanField
	 */
	private function beanHelperConvert2Descriptor(BeanFieldHelper $beanField){
		$descriptorBean = null;
		if (isset($beanField->getter->fkData)){
			$convertBean = new ForeignKeyFieldDescriptorBean();
		}else{
			$convertBean = new BaseFieldDescriptorBean();
		}
		
		$convertBean->fieldName = $beanField->columnName;
		$convertBean->getter = $beanField->getter->name;
		$convertBean->setter = $beanField->setter->name;
		$convertBean->label = $this->getLabelFromFieldName($beanField->columnName);
		$convertBean->name = self::toCamelCase($beanField->columnName, true)."Desc";
		$convertBean->isPk = $beanField->isPk;
		$convertBean->active = true;
		$convertBean->is_new = true;
		
		if (isset($beanField->getter->fkData)){
			/* @var $convertBean ForeignKeyFieldDescriptorBean */
			$convertBean->daoName = $this->_fitDaoByTableName($beanField->getter->fkData->refTable);
			$convertBean->daoData = $this->getDaoDataFromInstance($convertBean->daoName);
			$convertBean->dataMethod = array_search("getList", $convertBean->daoData->daoMethods) !== false ? "getList" : $convertBean->daoData->daoMethods[0];
			$convertBean->linkedIdGetter =  isset($convertBean->daoData->beanClassFields['id']) ? $convertBean->daoData->beanClassFields['id']->getter->name : "";
			$convertBean->linkedLabelGetter = isset($convertBean->daoData->beanClassFields['label']) ? $convertBean->daoData->beanClassFields['label']->getter->name : ""; 
		}
		
		
		$convertBean->renderer = $this->_match($beanField, $this->renderers);
		$convertBean->formatter = $this->_match($beanField, $this->formatters);
		$convertBean->validators = $this->_match($beanField, $this->validators, true);
		
		return $convertBean;
	}
	
	private function _match($beanField, $instances, $isMultiple = false){
		$matches = array();
		foreach ($this->handleOrder as $criteria) {
			switch ($criteria) {
				case 'pk':
					if (isset($instances[$criteria]) && isset($instances[$criteria]["pk"]) && $beanField->isPk){
						foreach ($instances[$criteria]["pk"] as $instance) {
							if (array_search($instance, $matches) === false){
								$matches[] = $instance;
							}
						}
					}
				;
				case 'type':
					if (isset($instances[$criteria]) && isset($instances[$criteria][$beanField->type])){
						foreach ($instances[$criteria][$beanField->type] as $instance) {
							if (array_search($instance, $matches) === false){
								$matches[] = $instance;
							}
						}
					}
				break;
				case 'php':
					if (isset($instances[$criteria]) && isset($instances[$criteria][$beanField->getter->phpType])){
						foreach ($instances[$criteria][$beanField->getter->phpType] as $instance) {
							if (array_search($instance, $matches) === false){
								$matches[] = $instance;
							}
						}
					}
				;
				break;
				case 'db':
					if (isset($instances[$criteria]) && isset($instances[$criteria][$beanField->getter->dbType])){
						foreach ($instances[$criteria][$beanField->getter->dbType] as $instance) {
							if (array_search($instance, $matches) === false){
								$matches[] = $instance;
							}
						}
					}
				;
				break;
			}
		}
		$return = $isMultiple ? $matches : (count($matches) ? $matches[0] : null);
		return $return;
	}
	
	private function _fitDaoByTableName($table){
		return $this->daos[$table];
	}
	
	private function _fitsMultiple($type, $list){
		return isset($list[$type]) ? $list[$type] : array(); 
	}
	
	private function _fits($type, $list){
		return isset($list[$type]) ? $list[$type][0] : null; 
	}
	
	private function getLabelFromFieldName($fieldName){
		return str_replace(" id", "", str_replace("_", " ", ucfirst($fieldName)));
	}
	
	/**
	 * Transforms a string to camelCase (except the first letter will be uppercase too).
	 * Underscores and spaces are removed and the first letter after the underscore is uppercased.
	 * 
	 * @param $str string
	 * @return string
	 */
	private static function toCamelCase($str, $fisrtLower = false) {
		if (!$fisrtLower){
			$str = strtoupper(substr($str,0,1)).substr($str,1);
		}
		while (true) {
			if (strpos($str, "_") === false && strpos($str, " ") === false)
				break;
				
			$pos = strpos($str, "_");
			if ($pos === false) {
				$pos = strpos($str, " ");
			}
			$before = substr($str,0,$pos);
			$after = substr($str,$pos+1);
			$str = $before.strtoupper(substr($after,0,1)).substr($after,1);
		}
		return $str;
	}
	
	public function getInstanceData($instanceName){
		$obj = new BCEFormInstanceBean();
		
		$desc = MoufManager::getMoufManager()->getInstanceDescriptor($instanceName);
		$prop = $desc->getProperty('mainDAO');
		$val = $prop->getValue();
		$obj->daoData = $this->getDaoData($val->getClassName());
		$obj->mainBeanTableName = $obj->daoData->beanTableName;
		
		$fieldDescs = array();
		
		$baseFiedDescriptors = $desc->getProperty('idFieldDescriptor');
		$val = $baseFiedDescriptors->getValue();
		$fieldData = $this->getFieldDescriptorBean($val);
		$obj->idFieldDescriptor = $fieldData;
		
		$baseFiedDescriptors = $desc->getProperty('fieldDescriptors');
		$val = $baseFiedDescriptors->getValue();
		if ($val){
			foreach ($val as $descriptor) {
				$fieldData = $this->getFieldDescriptorBean($descriptor);
				$fieldDescs[] = $fieldData;
			}
		}
		
		$obj->descriptors = $fieldDescs;
		
		
		$obj->action = $desc->getProperty('action')->getValue() ? $desc->getProperty('action')->getValue() : "save";
		$obj->method = $desc->getProperty('method')->getValue() ? $desc->getProperty('method')->getValue() : "POST";
		
		$obj->attributes = $desc->getProperty('attributes')->getValue();
		
		$rendererDesc = $desc->getProperty('renderer')->getValue();
		if ($rendererDesc) $obj->renderer = $rendererDesc->getName();

		$validateHandlerDesc = $desc->getProperty('validationHandler')->getValue();
		if ($rendererDesc) $obj->validationHandler= $validateHandlerDesc->getName();
		
		return $obj;
	}
	
	private function getFieldDescriptorBean($descriptor){
		if (!$descriptor) return null;
		
		$isCustom = false;
		
		$instance = MoufManager::getMoufManager()->getInstanceDescriptor($descriptor->getName());
		
		if ($descriptor->getClassName() == 'ForeignKeyFieldDescriptor'){
			$fieldData = new ForeignKeyFieldDescriptorBean();
		}else if ($descriptor->getClassName() == 'BaseFieldDescriptor'){
			$fieldData = new BaseFieldDescriptorBean();
		}else if ($descriptor->getClassName() == 'Many2ManyFieldDescriptor'){
			$fieldData = new Many2ManyFieldDescriptorBean();
		}else{
			$isCustom = true;
			$fieldData = new CustomFieldDescriptorBean();
		}
		
		if ($isCustom){
			$fieldData->name = $descriptor->getName();
		}else{
			$this->loadBaseValues($fieldData, $descriptor, $instance);
			
			if ($descriptor->getClassName() != 'Many2ManyFieldDescriptor'){
				$fieldData->getter = $instance->getProperty('getter')->getValue();
				$fieldData->setter = $instance->getProperty('setter')->getValue();
			}
			
			if ($descriptor->getClassName() == 'ForeignKeyFieldDescriptor'){
				$this->loadFKDescriptorValues($fieldData, $instance);
			}else if ($descriptor->getClassName() == 'Many2ManyFieldDescriptor'){
				$this->loadM2MDescriptorValues($fieldData, $instance);
			}
		}
		
		
		return $fieldData;
	}
	
	private function loadBaseValues(&$bean, $descriptor, $instance){
		/* @var $bean FieldDescriptorBean */
		$bean->name = $descriptor->getName();
		if ($instance->getProperty('renderer')->getValue()) 
			$bean->renderer = $instance->getProperty('renderer')->getValue()->getName();
		$formatterDesc = $instance->getProperty('formatter')->getValue();
		$bean->formatter = $formatterDesc ? $formatterDesc->getName() : null;
		$bean->fieldName = $instance->getProperty('fieldName')->getValue();
		$bean->label = $instance->getProperty('label')->getValue();
		
		$validatorsDesc = $instance->getProperty("validators");
		$bean->validators = array();
		if ($validatorsDesc){
			$validatorsDesc = $validatorsDesc->getValue();
			if ($validatorsDesc){
				foreach ($validatorsDesc as $validator) {
					$bean->validators[] = $validator->getName();
				}
			}
		}
	}
	
	private function loadFKDescriptorValues(&$fkDescBean, $instance){
		/* @var $fkDescBean ForeignKeyFieldDescriptorBean */
		$daoDesc = $instance->getProperty('dao')->getValue();
		if ($daoDesc){
			$fkDescBean->daoName = $daoDesc->getName();
			$fkDescBean->daoData = $this->getDaoData($daoDesc->getClassName());
		
			$fkDescBean->dataMethod = $instance->getProperty('dataMethod')->getValue();
			$fkDescBean->linkedIdGetter = $instance->getProperty('linkedIdGetter')->getValue();
			$fkDescBean->linkedLabelGetter = $instance->getProperty('linkedLabelGetter')->getValue();
		}
	}
	
	private function loadM2MDescriptorValues(&$m2mDescBean, $instance){
		/* @var $m2mDescBean Many2ManyFieldDescriptorBean */
		$mappingDaoDesc = $instance->getProperty("mappingDao")->getValue();
		if ($mappingDaoDesc){
			$m2mDescBean->mappingDaoName = $mappingDaoDesc->getName();
			$m2mDescBean->mappingDaoData = $this->getDaoData($mappingDaoDesc->getClassName());
			
			$m2mDescBean->mappingIdGetter = $instance->getProperty('mappingIdGetter')->getValue();
			$m2mDescBean->mappingLeftKeySetter = $instance->getProperty('mappingLeftKeySetter')->getValue();
			$m2mDescBean->mappingRightKeyGetter = $instance->getProperty('mappingRightKeyGetter')->getValue();
			$m2mDescBean->mappingRightKeySetter = $instance->getProperty('mappingRightKeySetter')->getValue();
			$m2mDescBean->beanValuesMethod = $instance->getProperty('beanValuesMethod')->getValue();
		}
		
		$linkedDaoDesc = $instance->getProperty("linkedDao")->getValue();
		if ($linkedDaoDesc){
			$m2mDescBean->linkedDaoName = $linkedDaoDesc->getName();
			$m2mDescBean->linkedDaoData = $this->getDaoData($linkedDaoDesc->getClassName());
			
			$m2mDescBean->linkedIdGetter = $instance->getProperty('linkedIdGetter')->getValue();
			$m2mDescBean->linkedLabelGetter = $instance->getProperty('linkedLabelGetter')->getValue();
			$m2mDescBean->dataMethod = $instance->getProperty('dataMethod')->getValue();
		}
	}
}
