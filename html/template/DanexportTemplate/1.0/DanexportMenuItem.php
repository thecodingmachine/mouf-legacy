<?php

/**
 * This class represent a menu item (a link)
 *
 * @Component
 */
class DanexportMenuItem implements HtmlElementInterface {
	
	/**
	 * The text for the menu item
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $menuText;
	
	/**
	 * The link for the menu (relative to the root url), unless it starts with / or http:// or https://.
	 *
	 * @Property
	 * @var string
	 */
	public $menuLink;
	
	/**
	 * The CSS class for the menu, if any (applied to the &lt;li&gt; element.
	 *
	 * @Property
	 * @var string
	 */
	public $menuCssClass;
	
	/**
	 * A list of parameters that are propagated by the link.
	 * For instance, if the parameter "mode" is set to 42 on the page (because the URL is http://mywebsite/myurl?mode=42),
	 * then if you choose to propagate the "mode" parameter, the menu link will have "mode=42" as a parameter.
	 *
	 * @Property
 	 * @var array<string>
	 */
	public $propagatedUrlParameters;
	
	/**
	 * This condition must be matched to display the menu.
	 * Otherwise, the menu is not displayed.
	 * The displayCondition is optional. If no condition is set, the menu will always be displayed. 
	 *
	 * @Property
	 * @var ConditionInterface
	 */
	public $displayCondition;
	
	/**
	 * Constructor.
	 *
	 * @param string $menuText
	 * @param string $menuLink
	 * @param string $menuCssClass
	 * @param array<string> $propagatedUrlParameters
	 */
	public function __construct($menuText=null, $menuLink=null, $menuCssClass=null, $propagatedUrlParameters=null) {
		$this->menuText = $menuText;
		$this->menuLink = $menuLink;
		$this->menuCssClass = $menuCssClass;
		$this->propagatedUrlParameters = $propagatedUrlParameters;
	}
	
	public function toHtml() {
		if ($this->displayCondition == null || $this->displayCondition->isOk($this)) {
			echo '<li ';
			if (!empty($this->menuCssClass)) {
				echo 'class="'.$this->menuCssClass.'"';
			}
			echo '>';
			if (!empty($this->menuLink)) {
				echo '<a href="'.str_replace('"', '&quot;', $this->getLinkWithParams()).'" >';
			}
			echo $this->menuText;
			if (!empty($this->menuLink)) {
				echo '</a>';
			}
			echo '</li>';
		}
	}

	private function getLinkWithParams() {
		$link = $this->getLink();
		
		$params = array();
		// First, get the list of all parameters to be propagated
		if (is_array($this->propagatedUrlParameters)) {
			foreach ($this->propagatedUrlParameters as $parameter) {
				if (isset($_REQUEST[$parameter])) {
					$params[$parameter] = get($parameter);
				}
			}
		}
		
		if (!empty($params)) {
			if (strpos($link, "?") === FALSE) {
				$link .= "?";
			} else {
				$link .= "&";
			}
			$paramsAsStrArray = array();
			foreach ($params as $key=>$value) {
				$paramsAsStrArray[] = urlencode($key).'='.urlencode($value);
			}
			$link .= implode("&", $paramsAsStrArray);
		}
		
		return $link;
	}
	
	private function getLink() {
		if (strpos($this->menuLink, "/") === 0
			|| strpos($this->menuLink, "http://") === 0
			|| strpos($this->menuLink, "https://") === 0
			|| strpos($this->menuLink, "javascript:") === 0) {
				return $this->menuLink;	
		}
		
		return ROOT_URL.$this->menuLink;
	}
	
}
?>