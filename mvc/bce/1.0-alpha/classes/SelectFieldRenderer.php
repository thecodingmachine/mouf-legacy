<?php
require_once 'SingleFieldRendererInterface.php';

/**
 * A renderer class that ouputs a simple select box: it doesn't handle multiple selection
 * 
 * @Component
 * @ApplyTo {"type": ["fk"]}
 */
class SelectFieldRenderer implements SingleFieldRendererInterface{
	
	/**
	 * 
	 * @var ForeignKeyFieldDescriptor
	 */
	private $descriptor;
	
	
	/**
	 * Tells if the field should display a select box or a radio button group
	 * @Property
	 * @var bool
	 */
	public $radioMode = false;
	
	/**
	 * Tells if the list should be alphnumerically sorted
	 * @Property
	 * @var bool
	 */
	public $sortAlpha = true;
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::render()
	 */
	public function render($descriptor){
		$this->descriptor = $descriptor;
		/* @var $descriptor ForeignKeyFieldDescriptor */
		/* @var $data TDBM_ObjectArray */
		$fieldName = $descriptor->getFieldName();
		$data = $descriptor->getData();
		$value = $descriptor->getFieldValue();
		$html = "";
		
		if ($this->sortAlpha){
			$data->uasort(array($this, "_compareAlpha"));
		}
		
		if (!$this->radioMode){
			$html = "<select name='$fieldName' id='$fieldName'>";
			foreach ($data as $linkedBean) {
				$beanId = $descriptor->getRelatedBeanId($linkedBean);
				$beanLabel = $descriptor->getRelatedBeanLabel($linkedBean);
				if ($beanId == $value) $selectStr = "selected = 'selected'";
				else $selectStr = "";
				$html .= "<option value='$beanId' $selectStr>$beanLabel</option>";
			}
			$html .= "</select>";
		}else{
			foreach ($data as $linkedBean) {
				$beanId = $descriptor->getRelatedBeanId($linkedBean);
				$beanLabel = $descriptor->getRelatedBeanLabel($linkedBean);
				$checkedStr = ($beanId == $value) ? "checked='checked'" : "";
				
				
				$html.="
					<label class='radio inline' for='$fieldName"."-"."$beanId'>
						<input type='radio' value='$beanId' $checkedStr id='$fieldName"."-"."$beanId' name='$fieldName'> $beanLabel
					</label>
				";
			}
		}
		return $html;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FieldRendererInterface::getJS()
	 */
	public function getJS($descriptor){
		return array();
	}
	
	public function _compareAlpha($bean1, $bean2){
		$beanLabel1 = $this->descriptor->getRelatedBeanLabel($bean1);
		$beanLabel2 = $this->descriptor->getRelatedBeanLabel($bean2);
		
		$ret = 0;
		if ($beanLabel1 > $beanLabel2){
			$ret = 1;
		}else if ($beanLabel1 < $beanLabel2){
			$ret = -1;
		}
		
		return $ret;
	}
	
}