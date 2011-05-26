<?php

/**
 * This class can be used to insert many js and css file.
 *
 * @Component
 */
class ScriptTagWidget implements HtmlElementInterface {
	
    /**
     * List of css file to add in header.
     * If you don't specify http://, the component automatically add ROOT_URL.
     *
     * @Property
     * @var array<string>
     */
    public $cssFiles;
    
    /**
     * List of js file to add in header.
     * If you don't specify http://, the component automatically add ROOT_URL.
     *
     * @Property
     * @var array<string>
     */
    public $jsFiles;
    
    /**
     * Display link to insert css and script to insert js
     * @see HtmlElementInterface::toHtml()
     */
	public function toHtml() {
		foreach ($this->cssFiles as $value) {
			if(strpos($value, 'http://') === false && strpos($value, 'https://') === false)
				$url = ROOT_URL.$value;
			else
				$url = $value;
			echo '<link href="'.$url.'" rel="stylesheet" type="text/css" />'."\n";
		}
		foreach ($this->jsFiles as $value) {
			if(strpos($value, 'http://') === false && strpos($value, 'https://') === false)
				$url = ROOT_URL.$value;
			else
				$url = $value;
			echo '<script type="text/javascript" src="'.$url.'"></script>'."\n";
		}
	}
}
?>