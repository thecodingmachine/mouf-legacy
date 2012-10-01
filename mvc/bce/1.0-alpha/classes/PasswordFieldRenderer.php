<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple text fields
 * @Component
 * @ApplyTo { "php" :[ "string", "int", "number"] }
 */
class PasswordFieldRenderer implements FieldRendererInterface{
	
	/**
	 * Autocomplete the field in the form.
	 * 
	 * @Property
	 * @var boolean
	 */
	public $autocomplete = false;
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		/* @var $descriptor BaseFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='password' ".($this->autocomplete ? "autocomplete='on'" : "autocomplete='off'")." value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array();
	}
	
}