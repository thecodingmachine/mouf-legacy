<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple text fields
 * @Component
 */
class TextFieldRenderer implements FieldRendererInterface{
	
	public function render(FieldDescriptorInterface $descriptor){
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='text' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
}