<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple text fields
 * @Component
 * @ApplyTo { "php" :[ "string", "int", "number", "boolean", "timestamp", "datetime", "date" ], "type": ["base"] }
 */
class TextFieldRenderer implements FieldRendererInterface{
	
	public function render($descriptor){
		/* @var $descriptor FieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='text' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
}