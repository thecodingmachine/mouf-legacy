<?php

/**
 * Writes the HTML that includes the CSS files and Javascript files needed for the top menu.
 *
 * @Component
 */
class HtmlMenuTopRubanHead implements HtmlElementInterface {
	
	public function toHtml() {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ROOT_URL.'plugins/html/template/menus/toprubanmenu/1.0/toprubanmenu.css" media="screen" />'."\n";
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/html/template/menus/toprubanmenu/1.0/toprubanmenu.js"></script>';
	}
}

?>