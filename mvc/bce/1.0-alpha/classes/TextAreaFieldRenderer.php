<?php
require_once 'FieldRendererInterface.php';

/**
 * Base class for rendering simple text area fields
 * @Component
 * @ApplyTo { "php" :[ "string" ] }
 */
class TextAreaFieldRenderer implements FieldRendererInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		/* @var $descriptor BaseFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return '<textarea name="'.$fieldName.'" id="'.$fieldName.'">'.$value.'</textarea>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array();
	}
	
}