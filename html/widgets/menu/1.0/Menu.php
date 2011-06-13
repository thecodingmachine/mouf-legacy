<?php

/**
 * This class represent a menu (full of menu items).
 * It is important to note that a menu item does not render directly in HTML (it has no toHtml method).
 * Instead, you must use another class (a Menu renderer class) to display the menu.
 * Usually, menu renderers are embedded into templates.
 * 
 * @Component
 */
class Menu implements MenuInterface {
	
	/**
	 * The children menu item of this menu (if any).
	 * 
	 * @var array<MenuItemInterface>
	 */
	private $children;
	
	/**
	 * This condition must be matched to display the menu.
	 * Otherwise, the menu is not displayed.
	 * The displayCondition is optional. If no condition is set, the menu will always be displayed. 
	 *
	 * @var ConditionInterface
	 */
	private $displayCondition;
		
	/**
	 * Constructor.
	 *
	 * @param string $label
	 * @param string $url
	 */
	public function __construct($children=null) {
		$this->children = $children;
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
	 * Adds one child menu item to this menu item.
	 * 
	 * @param MenuItem $child
	 */
	public function addChild(MenuItem $child) {
		$this->children[] = $child;
	}
	
	/**
	 * If set, this display condition is tested. If it returns false, the menu will be hidden.
	 * 
	 * @Property
	 * @param ConditionInterface $displayCondition
	 */
	public function setDisplayCondition(ConditionInterface $displayCondition) {
		$this->displayCondition = $displayCondition;
	}	
	

	/**
	 * If this function returns true, the menu item should not be displayed.
	 * 
	 * @return bool
	 */
	public function isHidden() {
		if ($this->displayCondition == null) {
			return false;
		}
		return !$this->displayCondition->isOk();
	}
	
}
?>