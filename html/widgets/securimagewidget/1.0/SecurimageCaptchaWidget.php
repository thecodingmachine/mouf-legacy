<?php

/**
 * This class represent a captcha tag.
 *
 * @Component
 */
class SecurimageCaptchaWidget implements HtmlElementInterface {
	
	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $count = 0;
	
	/**
	 * The label of the widget.
	 *
	 * @Property
	 * @var string
	 */
	public $label;
	
	/**
	 * The id to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $id;
	
	/**
	 * The name attribute of the widget (the input box name).
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $name;

	/**
	 * The name of the CSS class to be used on the input box (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $css;
	
	/**
	 * The text that explains what a Captcha is.
	 *
	 * @Property
	 * @var string
	 */
	public $explainText;
	
	/**
	 * The text of the link used to reload the image.
	 *
	 * @Property
	 * @var string
	 */
	public $reloadText = "Reload image";
	
	/**
	 * Whether the label should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nLabel;
	
	/**
	 * Whether the explaination text should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nExplainText;

	/**
	 * Whether the reload link below the image should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nReloadText;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml() {
		self::$count++;
		$imageid = "mouf_captcha_image_".self::$count;
		
		echo "<div class='captcha'>\n";
		echo "<div class='halfcaptcha'>\n";
		echo "<label>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		echo "<input type='text'";
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}
		if ($this->css) {
			echo " class='".plainstring_to_htmlprotected($this->css)."'";
		}
		
		echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";
		echo "</input>\n";
		if ($this->enableI18nExplainText) {
			eMsg($this->explainText);
		} else {
			echo $this->explainText;
		}
		
		echo "</div>\n";
		
		echo "<div class='halfcaptcha'>\n";
		
		echo '<img src="'.ROOT_URL.'/plugins/utils/captcha/Securimage/2.0.1-beta/securimage_show.php" class="captcha" alt="CAPTCHA Image" id="'.$imageid.'" />';
		
		echo '<a class="small" href="#" onclick="document.getElementById(\''.$imageid.'\').src = \''.ROOT_URL.'/plugins/utils/captcha/Securimage/2.0.1-beta/securimage_show.php?\' + Math.random(); return false">';
		if ($this->enableI18nReloadText) {
			eMsg($this->reloadText);
		} else {
			echo $this->reloadText;
		}
		echo '</a>';
		
		echo "</div>\n";
		echo "<div style='clear:both'></div>\n";
		echo "</div>\n";
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}	
	}
	
	/**
	 * Validates the current widget: returns true if the entered value is ok, false if it is ko.
	 * By default, the value is fetched from the request, but it can be passed manually via the argument.
	 *
	 * @return bool
	 */
	public function validate($value = null) {
		if ($value == null) {
			$value = get($this->name);
		}
		$securimage = new Securimage();		
		return $securimage->check($value);
	}
}
?>