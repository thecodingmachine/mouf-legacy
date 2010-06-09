<?php

FilterUtils::registerFilter("RequireHttps");

/**
 * Filter that requires the use of HTTPS (if enabled in the conf)
 * By passing @RequireHttps("yes"), an Exception is thrown if the action is called in HTTP.
 * By passing @RequireHttps("no"), no test is performed.
 * By passing @RequireHttps("redirect"), the call is redirected to HTTPS. Does only work with GET requests.
 */
class RequireHttpsAnnotation extends AbstractFilter
{
	
	public function __construct($value) {
		if (strpos($value, "yes") !== false) {
			$this->value = "yes";
		} else if (strpos($value, "no") !== false) {
			$this->value = "no";
		} else if (strpos($value, "redirect") !== false) {
			$this->value = "redirect";
		}
		
		if ($this->value == null) {
			throw new ApplicationException("annotation.requirehttps.error", "annotation.requirehttps.novalue");
		}
		if ($this->value != "yes" && $this->value != "no" && $this->value != "redirect") {
			throw new ApplicationException("annotation.requirehttps.error", "annotation.requirehttps.invalidvalue");
		}
	}
	
	/**
	 * The value passed to the filter.
	 */
	protected $value;

	/*public function setValue($value) {
		$this->value = $value;
	}*/

	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		$use_https = MoufManager::getMoufManager()->getInstance('splash')->supportsHttps;
		if ($use_https) {
			if ($this->value == "yes") {
				if (!isset($_SERVER['HTTPS'])) {
					throw new ApplicationException("annotation.requirehttps.requiresssl.title", "annotation.requirehttps.requiresssl.text");
				}
			} else if (!isset($_SERVER['HTTPS']) && $this->value == "redirect") {
				if ($_SERVER['REQUEST_METHOD'] != 'GET') {
					throw new ApplicationException("annotation.requirehttps.redirect.getonly.title", "annotation.requirehttps.redirect.getonly.text");
				}
				header("Location: ".$this->selfURL());
				exit;
			}
		}
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {

	}

	private function selfURL() {
		$protocol = "https";
		return $protocol."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}
	function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
}
?>