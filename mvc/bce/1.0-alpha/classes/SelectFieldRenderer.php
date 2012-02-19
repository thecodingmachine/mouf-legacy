<?php
require_once 'FieldRendererInterface.php';

/**
 * A renderer class that ouputs a simple select box: it doesn't handle multiple selection
 * TODO: add radio buttons since they will be fed by teh same data
 * @Component
 */
class SelectFieldRenderer implements FieldRendererInterface{
	
	public function render(FieldDescriptorInterface $descriptor){
		/* @var $descriptor ForeignKeyFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		
		$html = "<select name='$fieldName' id='$fieldName'>";
		foreach ($descriptor->data as $id => $label) {
			if ($id == $value) $selectStr = "selected = 'selected'";
			else $selectStr = "";
			$html .= "<option value='$id' $selectStr>$label</option>";
		}
		$html .= "</select>";
		
		return $html;
	}
	
}