<?php
require_once 'AbstractPaypalPaymentRequest.php';

/**
 * This class represents a subscription request to Paypal.
 * Subscription requests are simple beans that can be used to generate HTML subscribe buttons using the Paypal HTML generator.
 * Subscription requests contain all the necessary data regarding the payment.
 *
 * @Bean
 */
class PaypalSubscriptionRequest extends AbstractPaypalPaymentRequest {
	
	public function __construct() {
		parent::__construct();
		
		$this->paramsMap = array_merge($this->paramsMap, 
		 	array("itemName"=>"item_name", 
				"returnUrl"=>"return",
				"returnUrlBehaviour"=>"rm",
				"cancelReturnUrl"=>"cancel_return",
				"trialAmount1"=>"a1",
				"trialPeriod1"=>"p1",
				"trialPeriodUnit1"=>"t1",
				"trialAmount2"=>"a2",
				"trialPeriod2"=>"p2",
				"trialPeriodUnit2"=>"t2",
				"regularAmount"=>"a3",
				"regularPeriod"=>"p3",
				"regularPeriodUnit"=>"t3",
				"recurringPayment"=>"src",
				"reattemptOnFailure"=>"sra",
				"recurringTimes"=>"srt",
				"invoice"=>"invoice",
				"userManage"=>"usr_manage",
				"cn"=>"cn",
				"cs"=>"cs",
				"optionName0"=>"on0",
				"optionSelection0"=>"os0",
				"optionName1"=>"on1",
				"optionSelection1"=>"os1",
				"tax"=>"tax",
				"currencyCode"=>"currency_code",
				"modify"=>"modify",
				"country"=>"lc",
				"pageStyle"=>"page_style",
		 		"address1"=>"address1",
			 	"address2"=>"address2",
			 	"city"=>"city",
			 	"countryAddress"=>"country",
		 		"email"=>"email",
			 	"firstName"=>"first_name",
			 	"lastName"=>"last_name",
			 	"nightPhoneA"=>"night_phone_a",
			 	"nightPhoneB"=>"night_phone_b",
			 	"nightPhoneB"=>"night_phone_c",
			 	"state"=>"state",
			 	"zip"=>"zip"
				)
		 );
	}

						
	/**
	 * Returns the Paypal command to be used by paypal.
	 * For a subscription, this is: "_xclick-subscriptions".
	 *
	 */
	public function getCmd() {
		return "_xclick-subscriptions";
	}
	
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
	 * An internet URL where the user will be returned after completing the payment.
	 * For example, a URL on your site that hosts a ï¿½Information on your new subscriptionï¿½ page.
	 * If omitted, users will be taken to the PayPal site.
	 * 
	 * @var string
	 * @Property
	 */
	public $returnUrl;
	
	/**
	 * Return URL behavior.
	 * If set to ï¿½1ï¿½ and if a ï¿½returnï¿½ value is submitted, upon completion of the payment the buyer will 
	 * be sent back to the return URL using a GET method, and no transaction variables 
	 * will be submitted. If set to ï¿½2ï¿½ and if a ï¿½returnï¿½ value is submitted, the buyer 
	 * will be sent back to the return URL using a POST method, to which all available transaction 
	 * variables will also be posted. If omitted or set to ï¿½0ï¿½, GET methods will be used for all Subscriptions 
	 * transactions and Buy Now, Donations, or PayPal Shopping Cart transactions in which IPN is not enabled. 
	 * POST methods with variables will be used for the rest.
	 *
	 * Note: in this plugin, IPN is supposed to be enabled by default.
	 * 
	 * @var int
	 * @Property
	 * @OneOf(0,1,2)
	 * @OneOfText("(0) POST","(1) GET, no transaction","(2) POST, transaction passed")
	 */
	public $returnUrlBehaviour;
	
	/**
	 * An internet URL where the user will be returned if payment is cancelled.
	 * For example, a URL on your site which hosts a ï¿½Payment Cancelledï¿½ page.
	 * If if omitted, users will be taken to the PayPal site.
	 *
	 * @var string
	 * @Property
	 */
	public $cancelReturnUrl;
	
	/**
	 * Trial amount 1.
	 * This is the price of the first trial period. For a free trial, use a value of 0
	 *
	 * @var float
	 * @Property
	 */
	public $trialAmount1;
	
	/**
	 * Trial period 1.
	 * This is the length of the first trial period. The number is modified by the trial period 1 units (trialPeriodUnit1, below)
	 * 
	 * @var int
	 * @Property
	 */
	public $trialPeriod1;
	
	/**
	 * Trial period 1 units.
	 * This is the units of trial period 1 (above). Acceptable values are: D (days), W (weeks), M (months), Y (years)
	 *
	 * @var string
	 * @Property
	 * @OneOf("D", "W", "M", "Y")
	 * @OneOfText("(D) Days", "(W) Weeks", "(M) Months", "(Y) Years");
	 */
	public $trialPeriodUnit1;
	
	/**
	 * Trial amount 2.
	 * This is the price of the first trial period. There can be a second trial period only if there is a first trial period declared.
	 *
	 * @var float
	 * @Property
	 */
	public $trialAmount2;
	
	/**
	 * Trial period 2.
	 * This is the length of the first trial period. The number is modified by the trial period 2 units (trialPeriodUnit2, below)
	 * 
	 * @var int
	 * @Property
	 */
	public $trialPeriod2;
	
	/**
	 * Trial period 2 units.
	 * This is the units of trial period 2 (above). Acceptable values are: D (days), W (weeks), M (months), Y (years)
	 *
	 * @var string
	 * @Property
	 * @OneOf("D", "W", "M", "Y")
	 * @OneOfText("(D) Days", "(W) Weeks", "(M) Months", "(Y) Years");
	 */
	public $trialPeriodUnit2;
	
	/**
	 * Regular rate. This is the price of the subscription.
	 *
	 * @var float
	 * @Property
	 * @Compulsory
	 */
	public $regularAmount;
	
	/**
	 * Regular billing cycle.
	 * This is the length of the billing cycle.
	 * The number is modified by the regular billing cycle units (regularPeriodUnit, below)
	 * 
	 * @var int
	 * @Property
	 */
	public $regularPeriod;
	
	/**
	 * Regular billing cycle units.
	 * This is the units of the regular billing cycle (p3, above).
	 * Acceptable values are: D (days), W (weeks), M (months), Y (years)
	 *
	 * @var string
	 * @Property
	 * @OneOf("D", "W", "M", "Y")
	 * @OneOfText("(D) Days", "(W) Weeks", "(M) Months", "(Y) Years");
	 */
	public $regularPeriodUnit;
	
	/**
	 * Recurring payments.
	 * If set to ï¿½1,ï¿½ the payment will recur unless your customer cancels the subscription 
	 * before the end of the billing cycle. 
	 * If omitted, the subscription payment will not recur at the end of the billing cycle
	 *
	 * @var int
	 * @Property
	 * @OneOf(0,1)
	 * @OneOfText("(0) No","(1) Yes")
	 */
	public $recurringPayment;
	
	/**
	 * Recurring Times. This is the number of payments which will occur at the regular rate. 
	 * If omitted, payment will continue to recur at the regular rate until the subscription is cancelled
	 *
	 * @var int
	 * @Property
	 */
	public $recurringTimes;
	
	/**
	 * Reattempt on failure.
	 * If set to ï¿½1,ï¿½ and the payment fails, the payment will be reattempted two more times.
	 * After the third failure, the subscription will be cancelled.
	 * If omitted and the payment fails, payment will not be reattempted and the subscription 
	 * will be immediately cancelled
	 *
	 * @var int
	 * @Property
	 * @OneOf(0,1)
	 * @OneOfText("(0) No","(1) Yes")
	 */
	public $reattemptOnFailure;
	
	/**
	 * User-defined field (maximum 255 characters) which will be passed through the system 
	 * and returned to user in payment notification emails. This field will not be shown to your subscribers
	 *
	 * @var string
	 * @Property
	 */
	//public $custom;
	
	/**
	 * User- defined field (maximum 127 characters) which must be unique with each subscription.
	 * The invoice number will be shown to subscribers with the other details of their transactions
	 * 
	 * @var string
	 * @Property
	 */
	public $invoice;
	
	/**
	 * Username and password generation field. If set to ï¿½1ï¿½ PayPal will generate usernames 
	 * and passwords for your subscribers. For use with Password Management (see page 33). 
	 * If omitted, no passwords will be generated
	 *
	 * @var int
	 * @Property
	 * @OneOf(0,1)
	 * @OneOfText("(0) No","(1) Yes")
	 */
	public $userManage;
	
	/**
	 * Label that will appear above the note field (maximum 30 characters).
	 * This value is not saved and will not appear in any of your notifications.
	 * If omitted, no variable will be passed back to you. ï¿½Special instructions (optional):ï¿½ 
	 * will be displayed.
	 *
	 * @var string
	 * @Property
	 */
	public $cn;
	
	/**
	 * Sets the background color of your payment pages. If set to ï¿½1,ï¿½ the background color 
	 * will be black. If omitted or set to ï¿½0ï¿½ the background color will be white
	 *
	 * @var int
	 * @Property
	 * @OneOf(0,1)
	 * @OneOfText("(0) No","(1) Yes")
	 */
	public $cs;
	
	/**
	 * First option field name (maximum 30 characters). If omitted, no variable will be passed back to you
	 *
	 * @var string
	 * @Property
	 */
	public $optionName0;
	
	/**
	 * First set of option value(s). 
	 * If this option is selected through a text box (or radio button), each value should be no 
	 * more than 30 characters. If this value is entered by the customer through a text box, 
	 * there is a 200-character limit. If omitted, no variable will be passed back to you. 
	 * ï¿½on0ï¿½ must be defined in order for ï¿½os1ï¿½ to be recognized
	 *
	 * @var string
	 * @Property
	 */
	public $optionSelection0;
	
	/**
	 * Second option field name (maximum 30 characters). If omitted, no variable will be passed back to you
	 *
	 * @var string
	 * @Property
	 */
	public $optionName1;
	
	/**
	 * Second set of option value(s).
	 * If this option is selected through a text box (or radio button), each value should be no 
	 * more than 30 characters. If this value is entered by the customer through a text box, 
	 * there is a 200-character limit. If omitted, no variable will be passed back to you. 
	 * ï¿½on1ï¿½ must be defined in order for ï¿½os1ï¿½ to be recognized
	 *
	 * @var string
	 * @Property
	 */
	public $optionSelection1;
	
	/**
	 * Transaction-based tax override variable.
	 * Set to a flat tax amount you would like to apply to the transaction regardless of the 
	 * buyerï¿½s location. If present, this value overrides any tax settings that may be set in 
	 * the sellerï¿½s Profile. If omitted, Profile tax settings (if any) will apply
	 *
	 * @var float
	 * @Property
	 */
	public $tax;
	
	/**
	 * The currency of the payment.
	 * Defines the currency in which the monetary variables (amount, shipping, shipping2, handling, tax)
	 * are denoted.
	 * See table of supported currency codes: https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes
	 * If omitted, all monetary fields are interpreted as U.S. Dollars
	 *
	 * TODO: restrict with a OneOf list.
	 * 
	 * @var string
	 * @Property
	 */
	public $currencyCode;
	
	/**
	 * Modification behavior.
	 * 0 or null = The button allows buyers to only create new subscriptions.
	 * 1 = The button allows buyers to modify current subscriptions if they have any and to sign up for new subscriptions if they do not.
	 * 2 = The button allows buyers only to modify existing subscriptions according to the other parameters specified by the button and does not allow sign-up for new subscriptions.
	 *
	 * @var string
	 * @Property
	 * @OneOf(0,1,2)
	 * @OneOfText("(0) Create","(1) User can modify one of its subscriptions", "(2) Modify subscription only")
	 */
	public $modify;
	
	/**
	 * Sets the default country and associated language for the login or signup page that 
	 * your customers see when they click your button. If this variable is absent, the 
	 * default will be set from the userï¿½s cookie, or will be set to the U.S. if there is no cookie. 
	 * Can be set to any of the countries currently available on PayPal.
	 *
	 * @var string
	 * @Property
	 */
	public $country;

	/**
	 * Street (1 of 2 fields)
	 * 
	 * @var string
	 * @Property
	 */
	public $address1;
	
	/**
	 * Street (2 of 2 fields)
	 * 
	 * @var string
	 * @Property
	 */
	public $address2;
	
	/**
	 * City
	 * 
	 * @var string
	 * @Property
	 */
	public $city;
	
	/**
	 * Sets shipping and billing country.
	 * For allowable values, see Countries and Regions Supported by PayPal.
	 * 	https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_nvp_country_codes
	 * 
	 * @var string
	 * @Property
	 */
	public $countryAddress;
	
	/**
	 * Email address
	 * 
	 * @var string
	 * @Property
	 */
	public $email;
	
	/**
	 * First name
	 * 
	 * @var string
	 * @Property
	 */
	public $firstName;
	
	/**
	 * Last name
	 * 
	 * @var string
	 * @Property
	 */
	public $lastName;
	
	/**
	 * The area code for U.S. phone numbers, or the country code for phone numbers outside the U.S. This will prepopulate the payer’s home phone number.
	 * 
	 * @var string
	 * @Property
	 */
	public $nightPhoneA;
	
	/**
	 * The three-digit prefix for U.S. phone numbers, or the entire phone number for phone numbers outside the U.S., excluding country code. This will prepopulate the payer’s home phone number.
	 * 
	 * @var string
	 * @Property
	 */
	public $nightPhoneB;
	
	/**
	 * The four-digit phone number for U.S. phone numbers. This will prepopulate the payer’s home phone number.
	 * 
	 * @var string
	 * @Property
	 */
	public $nightPhoneC;
	
	/**
	 * State; use Official U.S. Postal Service Abbreviations.
	 * 	http://www.usps.com/ncsc/lookups/abbreviations.html#states
	 * @var string
	 * @Property
	 */
	public $state;

	/**
	 * Postal code
	 * 
	 * @var string
	 * @Property
	 */
	public $zip;
	
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