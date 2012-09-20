<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles text input fields with the jQuery MiniColor
 * @Component
 */
class ColorPickerRenderer implements FieldRendererInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		/* @var $descriptor BaseFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		return "<input type='text' value='".$value."' name='".$fieldName."' id='".$fieldName."' class='color-picker'/>";
	}
	
	/**
	 * (non-PHPdoc)
	 * The MiniColor depends on jQuery, and call the mini color picker initialization on dom ready
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array(
			"ready" => "
				function init() {
					// Enabling miniColors
					$('.color-picker').miniColors({
						change: function(hex, rgb) {
						},
						open: function(hex, rgb) {
						},
						close: function(hex, rgb) {
						}
					});
					
				}
				init();
			"
		);
	}
	
	
}