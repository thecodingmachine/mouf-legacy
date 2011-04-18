<?php

/**
 * This class represent a menu item.
 * It is important to note that a menu item does not render directly in HTML (it has no toHtml method).
 * Instead, you must use another class (a Menu renderer class) to display the menu.
 * Usually, menu renderers are embedded into templates.
 * 
 *
 * @Component
 */
class MenuItem implements MenuItemInterface {
	
	/**
	 * The text for the menu item
	 *
	 * @var string
	 */
	private $label;
	
	/**
	 * The link for the menu (relative to the root url), unless it starts with / or http:// or https://.
	 *
	 * @var string
	 */
	private $url;
	
	/**
	 * The children menu item of this menu (if any).
	 * 
	 * @var array<MenuItemInterface>
	 */
	private $children;
	
	/**
	 * The CSS class for the menu, if any.
	 * Use of this property depends on the menu implementation.
	 *
	 * @var string
	 */
	private $cssClass;
	
	/**
	 * A list of parameters that are propagated by the link.
	 * For instance, if the parameter "mode" is set to 42 on the page (because the URL is http://mywebsite/myurl?mode=42),
	 * then if you choose to propagate the "mode" parameter, the menu link will have "mode=42" as a parameter.
	 *
 	 * @var array<string>
	 */
	private $propagatedUrlParameters;
	
	/**
	 * This condition must be matched to display the menu.
	 * Otherwise, the menu is not displayed.
	 * The displayCondition is optional. If no condition is set, the menu will always be displayed. 
	 *
	 * @var ConditionInterface
	 */
	private $displayCondition;
	
	/**
	 * The translation service to use (if any) to translate the label text.
	 * 
	 * @var LanguageTranslationInterface 
	 */
	private $translationService;
	
	/**
	 * Whether the menu is in an active state or not.
	 * 
	 * @var bool
	 */
	private $isActive;
	
	/**
	 * Whether the menu is extended or not.
	 * This should not have an effect if the menu has no child.
	 * 
	 * @var bool
	 */
	private $isExtended;

	/**
	 * Level of priority used to order the menu items.
	 * 
	 * @var float
	 */
	private $priority;
	
	/**
	 * Constructor.
	 *
	 * @param string $label
	 * @param string $url
	 */
	public function __construct($label=null, $url=null, $children=null) {
		$this->label = $label;
		$this->menuLink = $menuLink;
		$this->children = $children;
	}

	/**
	 * Returns the label for the menu item.
	 * @return string
	 */
	public function getLabel() {
		if ($this->translationService) {
			return $this->translationService->getTranslation($this->label);
		} else {
			return $label;
		}
	}
	
	/**
	 * The label for this menu item.
	 * 
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * If any translation service is set, it will be used to translate the label.
	 * Otherwise, the label is displayed "as-is".
	 * 
	 * @Property
	 * @param LanguageTranslationInterface $translationInterface
	 */
	public function setTranslationService($translationService) {
		$this->translationService = $translationService;
	}

	/**
	 * Returns the URL for this menu (or null if this menu is not a link).
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * The link for the menu (relative to the root url), unless it starts with / or http:// or https://.
	 *
	 * @Property
	 * @var string
	 */
	public function setUrl($url) {
		$this->url = $url;
	}	
	
	/**
	 * Returns a list of children elements for the menu (if there are some).
	 * @return array<MenuItemInterface>
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * The children menu item of this menu (if any).
	 * 
	 * @Property
	 * @param array<MenuItemInterface> $children
	 */
	public function setChildren(array $children) {
		$this->children = $children;
	}

	/**
	 * Adds a menu item as a child of this menu item.
	 * 
	 * @param MenuItemInterface $menuItem
	 */
	public function addMenuItem(MenuItemInterface $menuItem) {
		$this->children[] = $menuItem;
	}
	
	/**
	 * Returns true if the menu is in active state (if we are on the page for this menu).
	 * @return bool
	 */
	public function isActive() {
		return $this->isActive;
	}
	
	/**
	 * Set the active state of the menu.
	 * 
	 * @Property
	 * @param bool $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}
	
	/**
	 * Enables the menu item (activates it).
	 * 
	 */
	public function enable() {
		$this->isActive = true;
	}
	
	/**
	 * Returns true if the menu should be in extended state (if we can see the children directly).
	 * @return bool
	 */
	public function isExtended() {
		return $this->isExtended;
	}

	/**
	 * Whether the menu is extended or not.
	 * This should not have an effect if the menu has no child.
	 * 
	 * @Property
	 * @param bool $isExtended
	 */
	public function setIsExtended($isExtended) {
		$this->isExtended = $isExtended;
	}
	
	/**
	 * Returns an optional CSS class to apply to the menu item.
	 * @return string
	 */
	public function getCssClass() {
		return $this->cssClass;
	}

	/**
	 * An optional CSS class to apply to the menu item.
	 * Use of this property depends on the menu implementation.
	 * 
	 * @Property
	 * @return string
	 */
	public function setCssClass($cssClass) {
		$this->cssClass = $cssClass;
	}

	/**
	 * Level of priority used to order the menu items.
	 * 
	 * @var float
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Returns the level of priority. It is used to order the menu items.
	 * @return float
	 */
	public function getPriority() {
		return $this->priority;
	}
	
	/**
	 * Returns true if this menu item is a separator.
	 * A separator is a special case of menu item that is juste here to separate menu items with a bar.
	 * It has no label, no URL, etc...
	 * 
	 * @return bool
	 */
	public function isSeparator() {
		return false;
	}
	
	/**
	 * If this function returns true, the menu item should not be displayed.
	 * 
	 * @return bool
	 */
	public function isHidden() {
		return !$this->displayCondition->isOk();
	}
	
	/**
	 * A list of parameters that are propagated by the link.
	 * For instance, if the parameter "mode" is set to 42 on the page (because the URL is http://mywebsite/myurl?mode=42),
	 * then if you choose to propagate the "mode" parameter, the menu link will have "mode=42" as a parameter.
	 *
	 * @param array<string> $propagatedUrlParameters
	 */
	public function setPropagatedUrlParameters($propagatedUrlParameters) {
		$this->propagatedUrlParameters = $propagatedUrlParameters;
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
			|| strpos($this->menuLink, "https://") === 0) {
			return $this->menuLink;	
		}
		
		return ROOT_URL.$this->menuLink;
	}
	
}
?>