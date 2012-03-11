<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * This class is in charge of rendering a menu. It contains a menu and can transform it in HTML using the toHtml() method.
 *
 * @Component
 */
class TopRibbonMenuRenderer implements HtmlElementInterface {
	
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
			
			echo '<div class="topribbonmenu"><div class="topribbonmenumaincontent">';
			
			$i=0;
			$mainMenu = array();
			if (is_array($menuItems)) {
				echo '<div class="submenu_list"></div>';
				foreach ($menuItems as $bigMenuItem) {
					/* @var $bigMenuItem MenuItem */
					$active = false;
					if (!$bigMenuItem->isHidden()) {
						$i++;
						/*
						echo '<ul class="menu" id="topribbonmenu_item'.$i.'" ';
						if ($bigMenuItem->isActive()) {
							echo " style='display:block'";
						}
						echo '>';
						*/
						echo '<div style="display:none" class="submenu_list" id="submenu_list_element_'.$i.'">';
						if (is_array($bigMenuItem->getChildren())) {
							foreach ($bigMenuItem->getChildren() as $item) {
								if($this->renderHtmlMenuItem($item))
									$active = true;
							}
						}
						echo '</div>';
						$bigMenuItem->setIsActive($active);
						$mainMenu[] = $bigMenuItem;
					}
					//echo '</ul>';
				}
			}
			
			$i=0;
			echo '<ul class="mainmenu">';
			foreach ($mainMenu as $menuItem) {
				/* @var $menuItem MenuItem */
				if (!$bigMenuItem->isHidden()) {
					$i++;
					echo '<li id="submenu_element_'.$i.'" class="submenu_element';
					if($menuItem->isActive())
						echo ' active';
					echo '">';
					if($menuItem->getLink()) {
						$url = str_replace('"', "&quot;", $url);
						echo '<a href="'.$url.'" onclick="return topribbonSubmenuList(\''.$i.'\')">'.$menuItem->getLabel().'</a>';
					}
					else
						echo '<a href="#" onclick="return topribbonSubmenuList(\''.$i.'\')">'.$menuItem->getLabel().'</a>';
					echo '</li>';
				}
			}
			echo '</ul>';
			
			/*
			echo '<div style="clear:both"></div>';
			echo '</div>';
			echo '<div class="topribbonmenutabs"><ul>';
			$i=0;
			if (is_array($menuItems)) {
				foreach ($menuItems as $item) {
					if (!$item->isHidden()) {
						echo "<li ";
						if ($item->isActive()) {
							echo " class='topribbonmenuactive'";
						}
						
						echo ">".$item->getLabel()."</li>";
					}
				}
			}
			echo '</ul></div>';
			*/
			echo '</div>';
			echo '</div>';
		}
	}
	
	private function renderHtmlMenuItem(MenuItemInterface $menuItem, $level = 1) {
		$active = false;
		if (!$menuItem->isHidden()) {
			$children = $menuItem->getChildren();
			if ($children) {
				echo '<div class="submenu_package">';
				echo '<div class="submenu_package_detail">';
				$i = 0;
				foreach ($children as $child) {
					/* @var $child MenuItemInterface */
					$i ++;
					if(($i - 1) % 3 == 0)
						echo '<div class="submenu_package_detail_group">';
					if($this->renderHtmlMenuItem($child, $level + 1))
						$active = true;
					if($i % 3 == 0)
						echo '</div>';
				}
				if($i % 3 != 0)
					echo '</div>';
				echo '</div>';
				echo '<div style="clear: both"></div>';
				echo '<span class="submenu_title">';
				$url = $menuItem->getLink();
				if($url) {
					$url = str_replace('"', "&quot;", $url);
					echo '<a href="'.$url.'">';
				}
				echo $menuItem->getLabel();
				if($url)
					echo '</a>';
				echo '</span>';
				echo '</div>';
			}
			else {
				if($level == 1) {
					echo '<div class="submenu_package">';
					echo '<div class="submenu_package_detail">';
					echo '<div class="submenu_package_detail_group">';
					
					echo '</div>';
					echo '</div>';
					echo '<div style="clear: both"></div>';
					echo '<span class="submenu_title">';
					$url = $menuItem->getLink();
					if($url) {
						$url = str_replace('"', "&quot;", $url);
						echo '<a href="'.$url.'">';
					}
					echo $menuItem->getLabel();
					if($url)
						echo '</a>';
					echo '</span>';
					echo '</div>';
				}
				else {
					$url = $menuItem->getLink();
					$url = str_replace('"', "&quot;", $url);
					
					echo "<div class='submenu_link'>";
					
					$menuItemStyleIcon = $menuItem->getAdditionalStyleByType('MenuItemStyleIcon');
					echo '<a ';
					if ($menuItem->isActive()) {
						echo 'class="active" ';
						$active = true;
					}
					echo 'href="'.$url.'">';
					if($menuItemStyleIcon) {
						//echo 'a';
						echo '<img src="'.$menuItemStyleIcon->getUrl().'" /> ';
					}
					
					echo '<span>';
					echo $menuItem->getLabel();
					echo '</span>';
					echo '</a>';
					echo '</div>';
				}
				/*
				$menuItemStyleIcon = $menuItem->getAdditionalStyleByType('MenuItemStyleIcon');
				if($menuItemStyleIcon) {
					echo '<a class="submenu_link_image"';
					if ($menuItem->isActive()) {
						echo 'class="active" ';
						$active = true;
					}
					echo 'href="'.$url.'">';
					//echo 'a';
					echo '<img src="'.$menuItemStyleIcon->getUrl().'" /> ';
					echo '</a>';
				}
				
				
				echo '<a class="submenu_link_text';
				if ($menuItem->isActive()) {
					echo ' active';
					$active = true;
				}
				echo '" href="'.$url.'">';
				echo $menuItem->getLabel();
				echo '</a>';
				echo "</div>";*/
				
			}
			/*
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
					/* @var $child MenuItemInterface *//*
					$this->renderHtmlMenuItem($child);
				}
				echo '</ul></div>';
			}
			if ($url) {
				echo '</a>';
			}
			echo '</li>';
			*/
		}
			return $active;
	}
	
}
?>