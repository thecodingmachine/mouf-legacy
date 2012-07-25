<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple text fields
 * @Component
 * @ApplyTo { "php" :[ "string", "int", "number"] }
 */
class TextFieldRenderer implements FieldRendererInterface{
	
	public function render($descriptor){
		/* @var $descriptor BaseFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='text' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getLibrary()
	 */
	public function getLibrary(){
		return null;
	}
}