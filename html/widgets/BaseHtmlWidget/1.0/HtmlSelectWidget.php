<?php

/**
 * This class represent a select HTML tag.
 * It can be directly bound to a datagrid to retrieve data.
 *
 * @Component
 */
class HtmlSelectWidget extends AbstractHtmlInputWidget {

	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $count = 0;
	
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
	 * Whether we should propose a default value that is NOT part of the datasource.
	 * This is useful to add a -- PICK ONE -- label at the beginning of the list. 
	 *
	 * @Property
	 * @var bool
	 */
	public $hasDefaultValue;
	
	/**
	 * The key used by the default value.
	 *
	 * @Property
	 * @var string
	 */
	//public $defaultKey;
	
	/**
	 * The string displayed for the default value (the top value).
	 *
	 * @Property
	 * @var string
	 */
	public $defaultValue = "-- PICK ONE --";
		
	/**
	 * Whether the default value should be internationalized or not.
	 * 
	 * @Property
	 * @var bool
	 */
	public $enableI18nDefaultValue;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_select_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
		echo "<select";
		echo " id='".plainstring_to_htmlprotected($id)."'";
		if ($this->css || $this->required) {
			$requiredClass = "";
			if ($this->required) {
				$requiredClass = "required ";
			}
			echo " class='".$requiredClass.plainstring_to_htmlprotected($this->css)."'";
		}
		echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";

		if ($this->hasDefaultValue) {
			//echo "<option value='".plainstring_to_htmlprotected($this->defaultKey)."'>";
			echo "<option value=''>";
			if ($this->enableI18nDefaultValue) {
				echo plainstring_to_htmlprotected(iMsg($this->defaultValue));
				
			} else {
				echo plainstring_to_htmlprotected($this->defaultValue);
			}
			echo "</option>\n";
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
			$defaultSelect = get($this->name, "string", false, null);
		}
		
		foreach ($values as $key=>$value) {
			echo "<option value='".plainstring_to_htmlprotected($key)."'";
			if ($defaultSelect != null && $key == $defaultSelect) {
				echo " selected='selected' ";	
			}
			echo ">";
			echo plainstring_to_htmlprotected($value);
			echo "</option>\n";
		}
		
		echo "</select>\n";
		
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