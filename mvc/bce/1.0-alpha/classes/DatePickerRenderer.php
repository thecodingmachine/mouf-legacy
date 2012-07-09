<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles date / timestamp input fields
* @ApplyTo { "php" :[ "string", "int", "number", "boolean", "timestamp", "datetime", "date" ] }
 * @Component
 */
class DatePickerRenderer implements FieldRendererInterface{
	
	public function render($descriptor){
		/* @var $descriptor FieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='text' value='".$value."' name='".$fieldName."' id='".$fieldName."'/>";
	}
	
	public function getJS($descriptor){
		/* @var $libManager WebLibraryManager */
		$fieldName = $descriptor->getFieldName();
		return array(
			"ready" => "$('#$fieldName').datepicker();"	
		);
	}
	
	
}