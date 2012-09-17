<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles date / timestamp input fields with the jQuery DatePicker
 * @ApplyTo { "php" :[ "string", "int", "number" ] }
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
	 * The datepicker depends on jQueryUI's datepicker widget, therefore load the library into the WebLibrary manager, and call the datepicker initialization on dom ready
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		/* @var $libManager WebLibraryManager */
		$jQueryUI = MoufManager::getMoufManager()->getInstance('jQueryUiLibrary');
		Mouf::getDefaultWebLibraryManager()->addLibrary($jQueryUI);
		
		$fieldName = $descriptor->getFieldName();
		
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