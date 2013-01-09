<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * This class represent a menu, with menu items in it.
 *
 * @Component
 */
class BootstrapMenu implements HtmlElementInterface {
	
	/**
	 * The menu items that make this menu
	 *
	 * @Property
	 * @Compulsory
	 * @var array<BootstrapMenuItem>
	 */
	public $menuItems;
	
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
	 * Initialize the object, optionnally with the array of menu items to be displayed.
	 *
	 * @param array<BootstrapMenuItem> $menuItems
	 */
	public function __construct($menuItems = null) {
		$this->menuItems = $menuItems;
	}
	
	public function toHtml() {
		if ($this->displayCondition == null || $this->displayCondition->isOk($this)) {
			echo '<ul class="nav">';
			
			if (is_array($this->menuItems)) {
				foreach ($this->menuItems as $item) {
					$item->toHtml();
				}
			}
				
			echo '</ul>';
		}
	}
	
}
?>