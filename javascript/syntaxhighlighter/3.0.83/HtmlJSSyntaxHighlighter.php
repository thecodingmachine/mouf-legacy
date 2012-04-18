<?php

/**
 * This class can be used to insert the JS and CSS files required to run SyntaxHighlighter.
 * Insert an instance of this class in the HEAD of your template and your code sample will automatically be highlighted.
 * See: <a href="http://alexgorbatchev.com/SyntaxHighlighter">http://alexgorbatchev.com/SyntaxHighlighter</a>
 *
 * @Component
 */
class HtmlJSSyntaxHighlighter implements HtmlElementInterface {
	
    /**
     * List of brushes loaded dynamically.
     * A brush = a supported language.
     *
     * See <a href="http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html">http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html</a> for more information.
     *
     * Brushes in this list will be loaded dynamically if a matching class is found on page load. 
     *
     * @Property
     * @var array<string, string> The key is the name of the language, the value the file of the brush.
     */
    public $brushes;
    
    /**
     * List of brushes loaded statically (each time the page is loaded).
     * A brush = a supported language.
     *
     * See <a href="http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html">http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html</a> for more information.
     *
     * @Property
     * @var array<string>
     */
    public $staticBrushes = array();
    
    
    /**
     * Theme file.
     *
     * @Property
     * @var string
     */
    public $theme;
    
    public function __construct() {
    	$this->theme = 'plugins/javascript/syntaxhighlighter/3.0.83/styles/shCoreDefault.css';
    	
    	$this->brushes = array(
    		"applescript" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushAppleScript.js',
	    	"as3" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushAS3.js',
	    	"bash" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushBash.js',
	    	"coldfusion" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushColdFusion.js',
	    	"cpp" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushCpp.js',
	    	"csharp" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushCSharp.js',
	    	"css" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushCss.js',
	    	"delphi" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushDelphi.js',
	    	"diff" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrush.js',
	    	"erlang" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushErlang.js',
	    	"groovy" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushGroovy.js',
	    	"java" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushJava.js',
	    	"javaFX" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrush.js',
	    	"jscript" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushJScript.js',
	    	"javascript" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushJScript.js',
	    	"js" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushJScript.js',
	    	"perl" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushPerl.js',
	    	"php" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushPhp.js',
	    	"plain" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushPlain.js',
	    	"powershell" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushPowerShell.js',
	    	"python" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushPython.js',
	    	"ruby" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushRuby.js',
	    	"sass" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushSass.js',
	    	"scala" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushScala.js',
	    	"sql" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushSql.js',
	    	"vb" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushVb.js',
	    	"xml" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushXml.js',
    		"html" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushXml.js',
    		"xhtml" => 'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shBrushXml.js',
    	);
    }
    
    /**
     * Display link to insert css and script to insert js
     * @see HtmlElementInterface::toHtml()
     */
	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shCore.js"></script>';
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shAutoloader.js"></script>';
		//echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/syntaxhighlighter/3.0.83/scripts/shCore.js"></script>';
		//echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/syntaxhighlighter/3.0.83/src/shAutoloader.js"></script>';
		foreach ($this->staticBrushes as $brush) {
			echo '<script type="text/javascript" src="'.ROOT_URL.$brush.'"></script>';
		}
		
		echo '<script type="text/javascript">';
		echo "jQuery(document).ready(function() {\n";
		$jsStringArray = array();
		foreach ($this->brushes as $key=>$value) {
			if(strpos($value, 'http://') === false && strpos($value, 'https://') === false) {
				$url = ROOT_URL.$value;
			} else {
				$url = $value;
			}
			$jsStringArray[] = json_encode($key." ".$url);
		}
		
		?>
		<?php 
		echo "\nSyntaxHighlighter.autoloader(";
		echo implode(",", $jsStringArray);
		echo ");\n";
		echo "SyntaxHighlighter.all();\n";
		echo "});\n";
		echo '</script>';
		
		echo '<link href="'.ROOT_URL.'plugins/javascript/syntaxhighlighter/3.0.83/styles/shCore.css" rel="stylesheet" type="text/css" />'."\n";
		if($this->theme) {
			if(strpos($this->theme, 'http://') === false && strpos($this->theme, 'https://') === false) {
				$url = ROOT_URL.$this->theme;
			} else {
				$url = $this->theme;
			}
			echo '<link href="'.$url.'" rel="stylesheet" type="text/css" />'."\n";
		}
	}
}
?>