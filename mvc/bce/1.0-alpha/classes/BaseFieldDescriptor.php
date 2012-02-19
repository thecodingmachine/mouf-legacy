<?php
require_once 'FieldDescriptorInterface.php';

/**
 * This class is the simpliest FieldDescriptor: 
 * it handles a field that has no "connections" to other objects (
 * as user name or login for example)
 * @Component
 */
class BaseFieldDescriptor implements FieldDescriptorInterface{
	
	/**
	 * Name of the field. This value must remain unique inside a form,
	 * it will be used for name and id attributes.
	 * @Property
	 * @var string
	 */
	public $fieldName;
	
	/**
	 * Optional formatter that will display a formatted value (example ).
	 * The formatter is also responsible for the reverse operation (01/30/2012 --> 2012-01-30)
	 * @var TO BE DEFINED : try using Mouf's existing formatters
	 */
	public $formatter;
	
	/**
	 * The name of the function that retruns the value of the field from the bean.
	 * For example, with $user->getLogin(), the $getter property should be "getLogin"
	 * @Property
	 * @var string
	 */
	public $getter;
	
	/**
	 * The value of the field once the FiedDescriptor has been loaded
	 * @Property
	 * @var mixed
	 */
	public $value;
	
	/**
	 * The renderer that will be responsible for delivering the HTML for that field
	 * @Property
	 * @var FieldRendererInterface 
	 */
	public $renderer;
	
	/**
	 * The validator of the field. Returns true/(false+errorString)
	 * @Property
	 * @var ValidatorInterface 
	 */
	public $validator;
	
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::load()
	 */
	public function load($mainBean){
		$fieldValue = call_user_func(array($mainBean, $this->getter));
		if ($this->formater) $this->value = $this->formater->format($fieldValue);
		$this->setFieldValue($fieldValue);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::getFieldName()
	 */
	public function getFieldName(){
		return $this->fieldName;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::getRenderer()
	 */
	public function getRenderer(){
		return $this->renderer;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::getValidator()
	 */
	public function getValidator(){
		return $this->validator;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::setFieldValue()
	 */
	public function setFieldValue($value){
		$this->value = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldDescriptorInterface::getFieldValue()
	 */
	public function getFieldValue(){
		return $this->value;
	}
	
}