<?php

/**
 * This class is in charge of rendering a menu. It contains a menu and can transform it in HTML using the toHtml() method.
 *
 * @Component
 */
class TopSliderMenuRenderer implements HtmlElementInterface {
	
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
			$menuItems = $this->menu->getChildren();
			
			echo '<div class="topslidermenu"><div class="topslidermenumaincontent">';
			
			$i=0;
			if (is_array($menuItems)) {
				foreach ($menuItems as $bigMenuItem) {
					/* @var $bigMenuItem MenuItem */
					if (!$bigMenuItem->isHidden()) {
						$i++;
						echo '<ul class="menu" id="topslidermenu_item'.$i.'" ';
						if ($bigMenuItem->isActive()) {
							echo " style='display:block'";
						}
						echo '>';
						if (is_array($bigMenuItem->getChildren())) {
							foreach ($bigMenuItem->getChildren() as $item) {
								$this->renderHtmlMenuItem($item);
							}
						}
						echo '</ul>';
					}
				}
			}
			
			echo '<div style="clear:both"></div>';
			echo '</div>';
			echo '<div class="topslidermenutabs"><ul>';
			$i=0;
			if (is_array($menuItems)) {
				foreach ($menuItems as $item) {
					if (!$item->isHidden()) {
						echo "<li ";
						if ($item->isActive()) {
							echo " class='topslidermenuactive'";
						}
						
						echo ">".$item->getLabel()."</li>";
					}
				}
			}
			echo '</ul></div>';
			echo '</div>';
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
				echo '<a ';
				if ($menuItem->isActive()) {
					echo 'class="active" ';
				}
				$url = str_replace('"', "&quot;", $url);
				echo 'href="'.$url.'" >';
			}
			echo $menuItem->getLabel();
			$children = $menuItem->getChildren();
			if ($children) {
				echo '<div class="content"><ul >';
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