<?php

/**
 * This class represent a simple HTML element, with a condition (that can evaluate to true or false to display or hide the component).
 *
 * @Component
 */
abstract class AbstractHtmlElement implements HtmlElementInterface {

	/**
	 * This condition will decide whether the component should be displayed or not.
	 * This can be useful to conditionally display the element.
	 * If empty, the component will always be displayed. 
	 * 
	 * @Property
	 * @var ConditionInterface
	 */
	public $displayCondition = null;
	
	/**
	 * This will hide the element (by setting the condition to false).
	 * The element will not be displayed.
	 */
	public function hideElement() {
		$this->displayCondition = MoufManager::getMoufManager()->getInstance("false.condition");
	}
	
	/**
	 * This will show the element (by setting the condition to true).
	 * The element will be displayed.
	 */
	public function showElement() {
		$this->displayCondition = MoufManager::getMoufManager()->getInstance("true.condition");
	}
	
	/**
	 * Evaluates the condition and returns true if the element should be displayed (in the HTML).
	 * 
	 * @return bool
	 */
	protected function isDisplayed() {
		if ($this->displayCondition != null && !$this->displayCondition->isOk($this)) {
			return false;
		} else {
			return true;
		}
	} 
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 * Note: the implementation only checks whether the display should occurs (based on the condition) and passes the
	 * display to the toHtmlElement() function which will perform the actual task. 
	 */
	public function toHtml() {
		if (!$this->isDisplayed()) {
			return;
		}
		return $this->toHtmlElement();
	}
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	abstract function toHtmlElement();
	
	
}
?>