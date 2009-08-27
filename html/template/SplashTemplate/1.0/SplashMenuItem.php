<?php

/**
 * This class represent a menu item (a link)
 *
 * @Component
 */
class SplashMenuItem implements HtmlElementInterface {
	
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
	 * @Compulsory
	 * @var string
	 */
	public $menuLink;
	
	/**
	 * The CSS class for the menu, if any (applied to the &lt;li&gt; element.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $menuCssClass;
	
	public function toHtml() {
		echo '<li ';
		if (!empty($this->menuCssClass)) {
			echo 'class="'.$this->menuCssClass.'"';
		}
		echo '><a href="'.$this->getLink().'" >'.$this->menuText.'</a>';
		echo '</li>';
		
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