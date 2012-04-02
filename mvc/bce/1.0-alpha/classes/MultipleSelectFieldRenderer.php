<?php
require_once 'FieldRendererInterface.php';

/**
 * A renderer class that ouputs multiple values field like checkboxes , multiselect list, ... fits for many to many relations  
 * @Component
 */
class MultipleSelectFieldRenderer implements FieldRendererInterface{
	
	/**
	 * Tells if the field should display 
	 * <ul>
	 * 	<li>a set of checkboxes,</li> 
	 *  <li>a multiselect list,</li>
	 *  <li>a multiselect widjet (TODO),</li>
	 *  <li>maybe a sortable dnd list (TODO)</li>
	 *  </ul>
	 * @OneOf("chbx", "multilist")
	 * @OneOfText("Checkboxes", "Multiselect List")
	 * @Property
	 * @var string
	 */
	public $mode = 'checkbox';
	
	public function render(FieldDescriptorInterface $descriptor){//TODO must be of type ForeignKeyFieldDescriptor
		/* @var $descriptor ForeignKeyFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$value = $descriptor->getFieldValue();
		switch ($this->mode) {
			case 'multiselect':
				$html = "<select name='$fieldName' id='$fieldName' multiple='multiple'>";
				foreach ($descriptor->data as $id => $label) {
					if ($id == $value) $selectStr = "selected = 'selected'";
					else $selectStr = "";
					$html .= "<option value='$id' $selectStr>$label</option>";
				}
				$html .= "</select>";
			break;
			
			case 'chbx':
				foreach ($descriptor->getData() as $id => $label) {
					$html .= "<label for='$fieldName"."-"."$id'>$label</label><input type='checkbox' name='".$fieldName."[]' id='$fieldName"."-"."$id' value='$id'/>";
				}	
			break;
		}
		return $html;
	}
	
}