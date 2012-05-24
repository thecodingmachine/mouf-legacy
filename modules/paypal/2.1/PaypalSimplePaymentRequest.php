<?php
require_once 'AbstractPaypalPaymentRequest.php';

/**
 * This class represents a subscription request to Paypal.
 * Subscription requests are simple beans that can be used to generate HTML subscribe buttons using the Paypal HTML generator.
 * Subscription requests contain all the necessary data regarding the payment.
 *
 * @Bean
 */
class PaypalSimplePaymentRequest extends AbstractPaypalPaymentRequest {
	
	public function __construct() {
		parent::__construct();
		
		$this->paramsMap = array_merge($this->paramsMap, 
		 	array(
		 		"amount"=>"amount",
		 		"currencyCode"=>"currency_code",
		 		"shipping"=>"shipping",
		 		"tax"=>"tax",
		 		"return"=>"return",
		 		"cancelReturn"=>"cancel_return",
		 		"itemName"=>"item_name",
		 		"lc"=>"lc"
			)
		 );
	}

	/**
	 * Returns the Paypal command to be used by paypal.
	 * For a subscription, this is: "_xclick".
	 *
	 */
	public function getCmd() {
		return "_xclick";
	}
	
	/**
	 * @var float
	 * @Property
	 */
	public $amount;
	
	/**
	 * Description of item being sold (maximum 127 characters).
	 * If you are collecting aggregate payments, this can include a summary of all items purchased, tracking numbers, or generic terms such as "subscription."
	 * If omitted, customer will see a field in which they have the option of entering an Item Name.
	 * 
	 * @var string
	 * @Property
	 */
	public $itemName;
	
	
	/**
	 * The currency code of the transaction (see https://cms.paypal.com/fr/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes)
	 * @var string
	 */
	public $currencyCode;
	
	/**
	* Transaction-based tax override variable.
	* Set to a flat tax amount you would like to apply to the transaction regardless of the
	* buyer�s location. If present, this value overrides any tax settings that may be set in
	* the seller�s Profile. If omitted, Profile tax settings (if any) will apply
	*
	* @var float
	* @Property
	*/
	public $tax;
	
	/**
	* An internet URL where the user will be returned after completing the payment.
	* For example, a URL on your site that hosts Information on your payement validation page.
	* If omitted, users will be taken to the PayPal site.
	*
	* @var string
	* @Property
	*/
	public $return;
	
	/**
	* An internet URL where the user will be returned if payment is cancelled.
	* For example, a URL on your site which hosts a "Payment Cancelled" page.
	* If if omitted, users will be taken to the PayPal site.
	*
	* @var string
	* @Property
	*/
	public $cancelReturn;

	/**
	* Default Language
	*
	* @var string
	* @Property
	*/
	public $lc;
	
	/**
	* Shipping fees
	*
	* @var float
	* @Property
	*/
	public $shipping;
	
	/**
	 * Returns an array associating the Paypal name to the value stored in the object.
	 *
	 * @return array
	 */
	public function getPaypalArgsArray() {
		$paypalArray = array();
		foreach ($this->paramsMap as $varName=>$paypalParam) {
			if ($this->$varName !== null) {
				$paypalArray[$paypalParam] = $this->$varName;
			}
		}
		$paypalArray["cmd"] = $this->getCmd();
		$paypalArray["no_note"] = 1;
		
		return $paypalArray;
	}
}
?>