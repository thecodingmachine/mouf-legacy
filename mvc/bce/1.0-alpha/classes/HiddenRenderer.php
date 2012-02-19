<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles hidden input fields
 * @Component
 */
class HiddenRenderer implements FieldRendererInterface{
	
	public function render(FieldDescriptorInterface $descriptor){
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='hidden' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
}