<?php

/**
 * This class contains all the parameters related to Paypal configuration.
 * Parameters in this class are not directly related to the payments but rather to the way your site interacts with Paypal.
 * This class will contain the IPN URL, the Paypal URL (therefore if you should use Paypal or the sandbox) and so on.
 * The parameters in this class will typically be different whether you are on a development or on a production environment.
 *
 * @Component
 */
class PaypalConfig {
	
	/**
	 * The Paypal URL.
	 * There are only 2 possible URLs: the real Paypal URL or the Paypal sandbox URL (for development mode).
	 *
	 * @var string
	 * @Property
	 * @Compulsory
	 * @OneOf("https://www.paypal.com/fr/cgi-bin/webscr", "https://www.sandbox.paypal.com/cgi-bin/webscr")
	 * @OneOfText("Paypal", "Sandbox Paypal (for testing purpose)")
	 */
	public $paypalUrl;
	
	/**
	 * The character set used to send the form data to Paypal.
	 * Paypal will answer the IPN with this character set too.
	 *
	 * Defaults to UTF-8.
	 * 
	 * @var string
	 * @Property
	 * @Compulsory
	 * @OneOf("Big5", "EUC-JP", "EUC-KR", "EUC-TW", "gb2312", "gbk", "HZ-GB-2312", "ibm-862", "ISO-2022-CN", "ISO-2022-JP", "ISO-2022-KR", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-13", "ISO-8859-15", "KOI8-R", "Shift_JIS", "UTF-7", "UTF-8", "UTF-16", "UTF-16BE", "UTF-16LE", "UTF16_Platform Endian", "UTF16_Opposite Endian", "UTF-32", "UTF-32BE", "UTF-32LE", "UTF32_Platform Endian", "UTF32_Opposite Endian", "US-ASCII", "windows-1250", "windows-1251", "windows-1252", "windows-1253", "windows-1254", "windows-1255", "windows-1256", "windows-1257", "windows-1258", "windows-874", "windows-949", "x-mac-greek", "x-mac-turkish", "x-maccentraleurroman", "x-mac-cyrillic", "ebcdic-cp-us", "ibm-1047")
	 */
	public $charset = "UTF-8";
	
	/**
	 * The notification URL the Paypal IPN will use to send back validation data.
	 * This points to your website, to the IPN notification script that is part of this Paypal plugin.
	 * 
	 * @var string
	 * @Property
	 * @Compulsory
	 */
	public $notifyUrl;
	
	/**
	 * This is your PayPal ID, or email address. This email address must be confirmed and linked to your Verified Business or Premier account
	 * Note: the email addresse MUST be in lower-case.
	 * 
	 * @var string
	 * @Property(validator="email")
	 * @Compulsory
	 */
	public $businessAccount;
	
	/**
	 * Returns an array associating the Paypal name to the value stored in the object.
	 *
	 * @return array
	 */
	public function getPaypalArgsArray() {
		$paypalArray = array();
		
		if ($this->charset !== null)
			$paypalArray["charset"] = $this->charset;
			
		if ($this->notifyUrl !== null)
			$paypalArray["notify_url"] = $this->notifyUrl;
			
		if ($this->businessAccount !== null)
			$paypalArray["business"] = $this->businessAccount;
		
		return $paypalArray;
	}
}
?>