<?php
session_start();
require_once '../../../../../Mouf.php';
require_once(dirname(__FILE__)."/../../../../../mouf/reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../../../../../mouf/Moufspector.php");
require_once(dirname(__FILE__)."/../admin/BCEAdminClasses.php");

$query = $_GET['q'];
$inputName = $_GET['n'];

$utils = new BCEUtils();

switch ($query) {
	case 'daoData':
		echo json_encode($utils->getDaoDataFromInstance($inputName));
	break;
	
	case 'instanceData':
		echo json_encode($utils->getInstanceData($inputName));
	break;
}

class BCEUtils{
	
	private $validators = array();
	private $renderers = array();
	private $formatters = array();
	private $daos = array();
	
	public $handleOrder = array("pk", "type", "php", "db");
	
	public function __construct(){
		$this->initValidators();
		
		$this->initRenderers();
		$this->initFormatters();
		$this->initDaos();
		
	}
	
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
	
	public function getDaoDataFromInstance($daoInstanceName){
		$desc = MoufManager::getMoufManager()->getInstanceDescriptor($daoInstanceName);
		$daoClass = $desc->getClassName();
		
		return $this->getDaoData($daoClass);
	}
	
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
	
	private function getDaoMethods($daoClass){
		$daoMethodNames = array();
		$daoClassReflexion = new MoufReflectionClass($daoClass);
		$daoMethods = $daoClassReflexion->getMethods();
		foreach ($daoMethods as $method) {
			$daoMethodNames[] = $method->getName();
		}
		return $daoMethodNames;
	}
	
	private function getBeanMethods($beanClassName){
		$beanClass = new MoufReflectionClass($beanClassName);
		$tableName = $beanClass->getAnnotations("dbTable");
		
		$parentBeanClass = $beanClass->getParentClass()->getParentClass();
		$methods = $beanClass->getMethodsByPattern("^[gs]et");
		$methodsParent = $parentBeanClass->getMethodsByPattern("^[gs]et");
		$finalMethods = array();

		$connection = Mouf::getDbConnection();
		$primaryKeys = $connection->getPrimaryKey($tableName[0]);
		
		foreach ($methods as $method) {
			/* @var $method  MoufReflectionMethod */
			if (!array_key_exists($method->getName(), $methodsParent)){
				
				$methodObj = new BeanMethodHelper();
				$methodObj->name = $method->getName();
					
				$returnAnnotation = $method->getAnnotations('dbType');
				$columnName = $method->getAnnotations('dbColumn');
				$columnName = $columnName[0];
				
				if (!$columnName){
					continue;
				}
				
				$fieldIndex = self::toCamelCase($columnName, true)."Desc";
				
				$fieldDataObj = isset($finalMethods[$fieldIndex]) ? $finalMethods[$fieldIndex] : new BeanFieldHelper();
				/* @var $fieldDataObj BeanFieldHelper */
				
				$fieldDataObj->columnName = $columnName;
				foreach ($primaryKeys as $key){
					if ($key->name == $columnName){
						$fieldDataObj->isPk = true;
						break;
					}
				}
				
				$referencedTables = $connection->getConstraintsOnTable($tableName[0], $columnName);
				if (!empty($referencedTables)){
					$ref = $referencedTables[0];
					$foreignKeyData = new ForeignKeyDataBean();
					$foreignKeyData->refTable = $ref['table2'];
					$foreignKeyData->refColumn = $ref['col2'];
					$methodObj->fkData = $foreignKeyData;
					$fieldDataObj->type = 'fk';
				}
					
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
		
		foreach ($finalMethods as $columnName => $fieldData) {
			$fieldData->asDescriptor = $this->beanHelperConvert2Descriptor($fieldData);
		}
		return array($finalMethods, $tableName[0]);
	}
	
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
		$convertBean->formater = $this->_match($beanField, $this->formatters);
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
		
		$m2mFiedDescriptors = $desc->getProperty('many2ManyFieldDescriptors');
		$val = $m2mFiedDescriptors->getValue();
		if ($val){
			foreach ($val as $descriptor) {
				$fieldData = $this->getFieldDescriptorBean($descriptor);
				$fieldDescs[] = $fieldData;
			}
		}
		
		$obj->descriptors = $fieldDescs;
		
		
		$obj->name = $desc->getProperty('name')->getValue();
		$obj->action = $desc->getProperty('action')->getValue();
		$obj->method = $desc->getProperty('method')->getValue();
		$obj->id = $desc->getProperty('id')->getValue();
		
		$rendererDesc = $desc->getProperty('renderer')->getValue();
		if ($rendererDesc) $obj->renderer = $rendererDesc->getName();

		$validateHandlerDesc = $desc->getProperty('validationHandler')->getValue();
		if ($rendererDesc) $obj->validationHandler= $validateHandlerDesc->getName();
		
		return $obj;
	}
	
	private function getFieldDescriptorBean($descriptor){
		if (!$descriptor) return null;
		
		$instance = MoufManager::getMoufManager()->getInstanceDescriptor($descriptor->getName());
		
		if ($descriptor->getClassName() == 'ForeignKeyFieldDescriptor'){
			$fieldData = new ForeignKeyFieldDescriptorBean();
		}else if ($descriptor->getClassName() == 'BaseFieldDescriptor'){
			$fieldData = new BaseFieldDescriptorBean();
		}else if ($descriptor->getClassName() == 'Many2ManyFieldDescriptor'){
			$fieldData = new Many2ManyFieldDescriptorBean();
		}
		
		
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
		
		return $fieldData;
	}
	
	private function loadBaseValues(&$bean, $descriptor, $instance){
		/* @var $bean FieldDescriptorBean */
		$bean->name = $descriptor->getName();
		if ($instance->getProperty('renderer')->getValue()) 
			$bean->renderer = $instance->getProperty('renderer')->getValue()->getName();
		$formaterDesc = $instance->getProperty('formatter')->getValue();
		$bean->formater = $formaterDesc ? $formaterDesc->getName() : null;
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
