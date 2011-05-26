<?php

/**
 * This class can be used to insert many js and css file.
 *
 * @Component
 */
class ScriptTagWidget implements HtmlElementInterface {
	
    /**
     * List of js file to add in header.
     * If you don't specify http://, the component automatically add ROOT_URL.
     *
     * @Property
     * @var array<string>
     */
    public $jsFiles;
    
    /**
     * List of css file to add in header.
     * If you don't specify http://, the component automatically add ROOT_URL.
     *
     * @Property
     * @var array<string>
     */
    public $cssFiles;
    
    
	public function toHtml() {
		foreach ($this->jsFiles as $value) {
			if(strpos($value, 'http://') === false)
				$url = ROOT_URL.$value;
			else
				$url = $value;
			echo '<script type="text/javascript" src="'.$url.'"></script>'."\n";
		}
		foreach ($this->cssFiles as $value) {
			if(strpos($value, 'http://') === false)
				$url = ROOT_URL.$value;
			else
				$url = $value;
			echo '<link href="'.$url.'" rel="stylesheet" type="text/css" />'."\n";
		}
	}
}
?>