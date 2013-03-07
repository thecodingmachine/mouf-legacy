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
	 * The navbar title
	 *
	 * @Property
	 * @var string
	 */
	public $title;
	
	/**
	 * The link the navbar title points to, relative to the ROOT_URL.
	 * Defaults to "".
	 *
	 * @Property
	 * @var string
	 */
	public $titleLink = "";
	
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
	 * If checked, the navbar will be rendered in dark shades instead of bright shades.
	 * 
	 * @var boolean
	 */
	public $inverted;
	
	/**
	 * Initialize the object, optionnally with the array of menu items to be displayed.
	 *
	 * @param array<HtmlElementInterface> $children
	 */
	public function __construct($children = array()) {
		$this->children = $children;
	}
	
	public function toHtml() {
		echo '<div class="navbar'.($this->inverted?' navbar-inverse':'').'">';
		echo '<div class="navbar-inner">';
		echo '<div class="container">';
				
		if ($this->title) {
			echo '<a class="brand" href="'.ROOT_URL.$this->titleLink.'">'.$this->title.'</a>';
		}
		
		echo '<ul class="nav " role="menu" aria-labelledby="dLabel">';
		foreach ($this->menuItems as $child) {
			$child->toHtml();
		}		
		echo '</ul>';
		
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	
}
?>