<?php
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
	 * @OneOf("chbx", "multiselect")
	 * @OneOfText("Checkboxes", "Multiselect List")
	 * @Property
	 * @var string
	 */
	public $mode = 'checkbox';
	
	public function render($descriptor){
		/* @var $descriptor Many2ManyFieldDescriptor */
		$fieldName = $descriptor->getFieldName();
		$values = $descriptor->getBeanValues();
		$html = "";
		switch ($this->mode) {
			case 'multiselect':
				$html = "<select name='$fieldName' id='$fieldName' multiple='multiple'>";
				foreach ($descriptor->getData() as $id => $label) {
					//TODO here :: change to check array search and select subset
					if (array_search($id, $values)!==false) $selectStr = "selected = 'selected'";
					else $selectStr = "";
					$html .= "<option value='$id' $selectStr>$label</option>";
				}
				$html .= "</select>";
			break;
			
			case 'chbx':
				foreach ($descriptor->getData() as $id => $label) {
					$checked = (array_search($id, $values)!==false) ? "checked='checked'" : "";
					$html .= "<label for='$fieldName"."-"."$id'>$label</label><input type='checkbox' $checked name='".$fieldName."[]' id='$fieldName"."-"."$id' value='$id'/>";
				}	
			break;
		}
		return $html;
	}
	
}