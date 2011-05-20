<?php

/**
 * This class is in charge of rendering a menu. It contains a menu and can transform it in HTML using the toHtml() method.
 *
 * @Component
 */
class SplashMenuRenderer implements HtmlElementInterface {
	
	/**
	 * The menu to render
	 *
	 * @Property
	 * @Compulsory
	 * @var MenuInterface
	 */
	public $menu;
	
	/**
	 * Initialize the object, optionnally with the array of menu items to be displayed.
	 *
	 * @param array<SplashMenuItem> $menuItems
	 */
	public function __construct($menu = null) {
		$this->menu = $menu;
	}
	
	public function toHtml() {
		if (!$this->menu->isHidden()) {
			echo '<div class="content"><ul class="menu">';
			
			$menuItems = $this->menu->getChildren();
			if (is_array($menuItems)) {
				foreach ($menuItems as $item) {
					$this->renderHtmlMenuItem($item);
				}
			}
				
			echo '</ul></div>';
		}
	}
	
	private function renderHtmlMenuItem(MenuItemInterface $menuItem) {
		if (!$menuItem->isHidden()) {
			echo '<li ';
			$menuCssClass = $menuItem->getCssClass();
			if (!empty($menuCssClass)) {
				echo 'class="'.$menuCssClass.'"';
			}
			echo '>';
			$url = $menuItem->getUrl();
			if ($url) {
				echo '<a href="'.$url.'" >';
			}
			echo $menuItem->getLabel();
			$children = $menuItem->getChildren();
			if ($children) {
				echo '<div class="content"><ul class="menu">';
				foreach ($children as $child) {
					/* @var $child MenuItemInterface */
					$this->renderHtmlMenuItem($child);
				}
				echo '</ul></div>';
			}
			if ($url) {
				echo '</a>';
			}
			echo '</li>';
		}
	}
	
}
?>