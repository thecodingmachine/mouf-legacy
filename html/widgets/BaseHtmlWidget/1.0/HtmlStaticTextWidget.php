<?php

/**
 * This class represent a simple text to be displayed in a form.
 * The class has a label, and a text.
 * This is useful to display some text instead of a disabled text input for instance.
 *
 * @Component
 */
class HtmlStaticTextWidget extends AbstractHtmlElement {
		
	private static $count = 0;
	
	/**
	 * The label of the widget.
	 *
	 * @Property
	 * @var string
	 */
	public $label;
	
	/**
	 * The label of the widget.
	 *
	 * @Property
	 * @var string
	 */
	public $text;
	
	/**
	 * The id to be used (if any) for the span element.
	 *
	 * @Property
	 * @var string
	 */
	public $id;
	
	/**
	 * The name of the CSS class to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $css;
	
	/**
	 * Whether the label should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nLabel;
	
	/**
	 * Whether the text should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nText;
	
	/**
	 * The formatters used to display the text.
	 * If no formatter is specified, content is displayed as text.
	 * 
	 * @Property
	 * @var array<FormatterInterface> $formatters
	 */
	public $formatters;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_statictextwidget_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		echo "<span";
		echo " id='".plainstring_to_htmlprotected($id)."'";

		if ($this->css) {
			echo " class='".plainstring_to_htmlprotected($this->css)."'";
		}
	
		echo " >";
		
		$text = $this->text;
		if (is_array($this->formatters)) {
			foreach ($this->formatters as $formatter) {
				$text = $formatter->format($text);
			}
		}
		if ($this->enableI18nText) {
			$text = iMsg($text);
		}
		
		echo $text;
		echo "</span>";
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}
		
	}
}
?>