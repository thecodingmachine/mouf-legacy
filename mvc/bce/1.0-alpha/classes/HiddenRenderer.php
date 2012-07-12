<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles hidden input fields
 * @ApplyTo { "pk" : [ "pk" ] }
 * @Component
 */
class HiddenRenderer implements FieldRendererInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		/* @var $descriptor FieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='hidden' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
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