<?php

/**
 * Writes the script that includes the JQuery-UI library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryUI implements HtmlElementInterface {
	
	/**
	 * If set to true, the default CSS (lightness) will be included with the Javascript.
	 * 
	 * @Property
	 * @OneOf "ui-lightness", "ui-darkness", "black-tie", "blitzer", "cupertino", "dark-hive", "dot-luv", "eggplant", "excite-bike", "flick", "hot-sneaks", "humanity", "le-frog", "mint-choc", "overcast", "pepper-grinder", "redmond", "smoothness", "south-street", "start", "sunny", "swanky-purse", "trontastic", "vader"
	 * @var string
	 */
	public $includeDefaultCss;
	
	public function toHtml() {
		if ($this->includeDefaultCss) {
			echo '<link type="text/css" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.8.23/css/'.$this->includeDefaultCss.'/jquery-ui-1.8.23.custom.css" rel="stylesheet" media="screen" />'."\n";
		}
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.8.23/js/jquery-ui-1.8.20.custom.min.js"></script>';
		
	}
}

?>
