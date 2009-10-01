<?php

/**
 * This class represent a menu, with menu items in it.
 *
 * @Component
 */
class SplashMenu implements HtmlElementInterface {
	
	/**
	 * The menu items that make this menu
	 *
	 * @Property
	 * @Compulsory
	 * @var array<SplashMenuItem>
	 */
	public $menuItems;
	
	/**
	 * Initialize the object, optionnally with the array of menu items to be displayed.
	 *
	 * @param array<SplashMenuItem> $menuItems
	 */
	public function __construct($menuItems = null) {
		$this->menuItems = $menuItems;
	}
	
	public function toHtml() {
		echo '<div class="content"><ul class="menu">';
		
		if (is_array($this->menuItems)) {
			foreach ($this->menuItems as $item) {
				$item->toHtml();
			}
		}
			
		echo '</ul></div>';
	}
	
}
?>