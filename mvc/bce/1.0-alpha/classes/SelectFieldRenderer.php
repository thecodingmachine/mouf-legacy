<?php
require_once 'FieldRendererInterface.php';

/**
 * A renderer class that ouputs a simple select box: it doesn't handle multiple selection
 * TODO: add radio buttons since they will be fed by teh same data
 * @Component
 */
class SelectFieldRenderer implements FieldRendererInterface{
	
	/**
	 * Tells if the field should display a select box or a radio button group
	 * @Property
	 * @var bool
	 */
	public $radioMode = false;
	
	public function render(FieldDescriptorInterface $descriptor){//TODO must be of type ForeignKeyFieldDescriptor
		/* @var $descriptor ForeignKeyFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		if (!$this->radioMode){
			$html = "<select name='$fieldName' id='$fieldName'>";
			foreach ($descriptor->data as $id => $label) {
				if ($id == $value) $selectStr = "selected = 'selected'";
				else $selectStr = "";
				$html .= "<option value='$id' $selectStr>$label</option>";
			}
			$html .= "</select>";
		}else{
			foreach ($descriptor->getData() as $id => $label) {
				$html .= "<label for='$fieldName"."-"."$id'>$label</label><input type='radio' name='$fieldName' id='$fieldName"."-"."$id' value='$id'/>";
			}
		}
		return $html;
	}
	
}