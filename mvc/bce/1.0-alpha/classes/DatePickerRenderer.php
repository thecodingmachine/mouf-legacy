<?php
require_once 'FieldRendererInterface.php';

/**
 * This renderer handles date / timestamp input fields
 * @ApplyTo { "php" :[ "timestamp", "datetime", "date" ] }
 * @Component
 */
class DatePickerRenderer implements FieldRendererInterface{

	/**
	 * @Property
	 * @var string $settings
	 * the JSON settings for the datpicker (see http://jqueryui.com/demos/datepicker/#options)
	 */
	public $settings;
	
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
		/* @var $libManager WebLibraryManager */
		$jQueryUI = MoufManager::getMoufManager()->getInstance('jQueryUiLibrary');
		Mouf::getDefaultWebLibraryManager()->addLibrary($jQueryUI);
		
		$fieldName = $descriptor->getFieldName();
		
		$settings = "";
		if ($this->settings){
			if (!json_decode($this->settings)){
				throw new Exception("Settings property of the DatePickerRenderer component is not a valid JSON string <pre>$this->settings</pre> given");
			}
			$settings = $this->settings;
		}
		
		return array(
			"ready" => "$('#$fieldName').datepicker($settings);"
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getLibrary()
	 */
	public function getLibrary(){
	}
	
	
}