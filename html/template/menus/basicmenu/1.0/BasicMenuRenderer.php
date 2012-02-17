<?php

/**
 * This class is in charge of rendering a menu. It contains a menu and can transform it in HTML using the toHtml() method.
 * <p>The rendering is performed using &lt;ul&gt; and &lt;li&gt; tags.</p>
 * 
 * @Component
 */
class BasicMenuRenderer implements HtmlElementInterface {
	
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
		if ($this->menu && !$this->menu->isHidden()) {
			echo '<ul class="menu">';
			
			$menuItems = $this->menu->getChildren();
			if (is_array($menuItems)) {
				foreach ($menuItems as $item) {
					$this->renderHtmlMenuItem($item);
				}
			}
				
			echo '</ul>';
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
			$url = $menuItem->getLink();
			if ($url) {
				echo '<a href="'.$url.'" >';
			}
			echo $menuItem->getLabel();
			if ($url) {
				echo '</a>';
			}
			$children = $menuItem->getChildren();
			if ($children) {
				echo '<ul class="menu">';
				foreach ($children as $child) {
					/* @var $child MenuItemInterface */
					$this->renderHtmlMenuItem($child);
				}
				echo '</ul>';
			}
			
			echo '</li>';
		}
	}
	
}
?>