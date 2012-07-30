<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple boolean fields
 * @Component
 * @ApplyTo { "php" :[ "boolean" ] }
 */
class BooleanFieldRenderer implements FieldRendererInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		/* @var $descriptor BaseFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$strChecked = $descriptor->getFieldValue() ? "checked = 'checked'" : "";
		return "<input type='checkbox' value='1' name='$fieldName' id='$fieldName' $strChecked/>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array();
	}
	
}