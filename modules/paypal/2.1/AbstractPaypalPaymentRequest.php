<?php

/**
 * This class represents an abstract payment request to Paypal.
 * Payment requests are simple beans that can be used to generate HTML payment buttons using the Paypal HTML generator.
 * Payment requests contain all the necessary data regarding the payment.
 * Use one of the subclass to perform a real payment:
 * - PaypalPaymentRequest to perform a single payment
 * - PaypalSubscriptionRequest to perform a subscription
 *
 */
abstract class AbstractPaypalPaymentRequest {
	
	/**
	 * A private table mapping properties names to HTTP parameters.
	 */
	protected $paramsMap;
	
	/**
	 * Returns the Paypal command: whether we are sending to Paypal a unique payment (or a gift), a cart, or a subscription request.
	 */
	abstract public function getCmd();
	
	/**
	 * Returns an array associating the Paypal name to the value stored in the object.
	 *
	 * @return array
	 */
	abstract public function getPaypalArgsArray();
	
	
	public function __construct() {
		$this->paramsMap = 
		 	array(
		 		"noShipping"=>"no_shipping",
				"pageStyle"=>"page_style"
		 );
	}
	
	/**
	 * Prompt buyer for shipping address. Allowed values are:
	 * - 0: (default) buyer is prompted to include a shipping address.
	 * - 1: buyer is not asked for a shipping address
	 * - 2: buyer must provide a shipping address
	 *
	 * @var int
	 * @OneOf(0,1,2)
	 * @OneOfText("(0) (default) buyer is prompted to include a shipping address.","(1) buyer is not asked for a shipping address", "(2) buyer must provide a shipping address")
	 */
	public $noShipping;
	
	/**
	 * Sets the Custom Payment Page Style for payment pages associated with this button/link. 
	 * The value of page_style is the same as the Page Style Name you chose when adding or editing 
	 * the page style. You can add and edit Custom Payment Page Styles from the Profile section of 
	 * the My Account tab. If you would like the button/link to always reference the style you make Primary, 
	 * set this variable equal to "primary." If you would like the button/link to reference the 
	 * default PayPal page style, set this variable equal to "paypal." The page_style variable has a 
	 * maximum length of 30 characters. Valid character set is alphanumeric ASCII lower 7-bit characters 
	 * only, plus underscore. It cannot include spaces
	 *
	 * @var string
	 * @Property
	 */
	public $pageStyle;
}

?>