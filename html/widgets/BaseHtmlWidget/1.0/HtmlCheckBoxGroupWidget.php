<?php

/**
 * This class represent a group of Checkboxes.
 * It can be directly bound to a datagrid to retrieve data.
 *
 * @Component
 */
class HtmlCheckBoxGroupWidget extends AbstractHtmlInputWidget {
		
	/**
	 * Datasource to populate the select box.
	 *
	 * @Property
	 * @var DataSourceInterface
	 */
	public $datasource;
	
	/**
	 * The column containing the label returned by the select box.
	 *
	 * @Property
	 * @var DataSourceColumnInterface
	 */
	public $columnLabel;
	
	/**
	 * Whether the values displayed inside the select box should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nValues;

	/**
	 * If the labels are internationalized, this prefix will be applied before internationalization.
	 *
	 * @Property
	 * @var string
	 */
	public $i18nValuesPrefix;
		
	/**
	 * Whether to sort the values or not
	 *
	 * @Property
	 * @var boolean
	 */
	public $sort = true;
	
	/**
	 * If this is set, the list of all checkboxes will be wrapped into a div with the class set.
	 *
	 * @Property
	 * @var string
	 */
	public $checkBoxListContainerCss;
	
	/**
	 * If this is set, each checkbox will be wrapped into a div with the class set.
	 *
	 * @Property
	 * @var string
	 */
	public $checkBoxContainerCss;
	
	public $checkBoxLabelCss;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		
		echo "<label>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
		// Remove any trailing [] from the name.
		$name = $this->name;		
	    $end = substr($name, strlen($name) - 2);
	    if ($end == "[]") {
	    	$name = substr($name, 0, strlen($name) - 2);
	    }
		
		$content = $this->datasource->getRows();
		
		
		// Let's start by translating the content, if needed.
		$values = array();
		$labelColumn = $this->columnLabel->getName();
		foreach ($content as $key=>$row) {
			if ($this->enableI18nValues) {
				$values[$key] = iMsg($this->i18nValuesPrefix.$row->$labelColumn);
			} else {
				$values[$key] = $row->$labelColumn;
			}
		}
		
		if ($this->sort) {
			asort($values);
		}
		
		$defaultSelect = null;
		if ($this->selectDefaultFromRequest) {
			$defaultSelect = get($this->name);
		}
		
		if ($this->checkBoxListContainerCss) {
				echo "<div class='".$this->checkBoxListContainerCss."'>\n";
		}
		
		foreach ($values as $key=>$value) {
			if ($this->checkBoxContainerCss) {
				echo "<div class='".$this->checkBoxContainerCss."'>\n";
			}
			
			echo "<input type='checkbox' name='".plainstring_to_htmlprotected($name)."[]' value='".plainstring_to_htmlprotected($key)."'";
			if ($this->css) {
				echo " class='".plainstring_to_htmlprotected($this->css)."'";
			}
		
			if ($this->disabled) {
				echo ' disabled="disabled"';
			}
			
			if ($defaultSelect != false) {
				if (array_search($key, $defaultSelect) !== false) {
					echo " checked='checked' ";	
				}
			}
			echo "/>";
			if ($this->css) {
				echo "<span class='".plainstring_to_htmlprotected($this->checkBoxLabelCss)."'>".plainstring_to_htmlprotected($value)."</span>";
			} else {
				echo plainstring_to_htmlprotected($value);
			}
			if ($this->checkBoxContainerCss) {
				echo "</div>\n";
			}
		}
		
		if ($this->checkBoxListContainerCss) {
			echo "</div>\n";
		}
	
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}
		
	}
}
?>