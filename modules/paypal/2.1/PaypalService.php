<?php
require_once 'PaypalConfig.php';
require_once 'PaypalSubscriptionRequest.php';
require_once 'PaypalSimplePaymentRequest.php';
require_once 'PaypalIpnResponse.php';
require_once 'PaypalEventHandlerInterface.php';
require_once 'PaypalPayment.php';

/**
 * The Paypal service class is used to redirect the user to Paypal in order to get payments.
 *
 * Requires to activate the php_openssl extension.
 *
 * @Component
 */
class PaypalService {

	private static $ipn_vars = array(
			"item_name",
			"business",
			"item_number",
			"payment_status",
			"mc_gross",
			"mc_currency",
			"txn_id",
			"receiver_email",
			"receiver_id",
			"quantity",
			"num_cart_items",
			"payment_date",
			"first_name",
			"last_name",
			"payment_type",
			"payment_gross",
			"payment_fee",
			"settle_amount",
			"memo",
			"payer_email",
			"txn_type",
			"payer_status",
			"address_street",
			"address_city",
			"address_state",
			"address_zip",
			"address_country",
			"address_status",
			"tax",
			"option_name1",
			"option_selection1",
			"option_name2",
			"option_selection2",
			"invoice",
			"custom",
			"notify_version",
			"verify_sign",
			"payer_business_name",
			"payer_id",
			"mc_fee",
			"exchange_rate",
			"settle_currency",
			"parent_txn_id",
			"pending_reason",
			"reason_code",
			"residence_country",
			"test_ipn",
			"charset",
			// subscription specific vars
			"subscr_id",
			"subscr_date",
			"subscr_effective",
			"period1",
			"period2",
			"period3",
			"amount1",
			"amount2",
			"amount3",
			"mc_amount1",
			"mc_amount2",
			"mc_amount3",
			"recurring",
			"reattempt",
			"retry_at",
			"recur_times",
			"username",
			"password",
			//auction specific vars
			"for_auction",
			"auction_closing_date",
			"auction_multi_item",
			"auction_buyer_id",
			"transaction_subject",
			"protection_eligibility");


	/**
	 * A list of objects that handle events received from Paypal.
	 * Objects in this list should implement the PaypalEventHandledInterface.
	 * These objects are responsible for handling a lot of events (subscription, payment, subscrition cancelled, hacking attempt, errors, etc...).
	 *
	 * @Property
	 * @Compulsory
	 * @var array<PaypalEventHandlerInterface>
	 */
	public $eventHandlers;

	/**
	 * The Paypal configuration.
	 *
	 *
	 * @Property
	 * @Compulsory
	 * @var PaypalConfig
	 */
	public $paypalConfig;

	/**
	 * The logger that will be used to write messages.
	 * Since messages for Paypal are quite important, it might be interesting to log them, and send mails when important messages occur.
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * The TDBM service that will be used to access the database.
	 *
	 * @Property
	 * @Compulsory
	 * @var TDBM_Service
	 */
	public $tdbmService;

	/**
	 * Redirects the user to the payment screen to request a subscription.
	 * This method will also create a new subscription in database, with a status set to "request".
	 * The subscription object will be passed in the "active" status if the payment is succesful.
	 *
	 * @param AbstractPaypalPaymentRequest $request
	 * @return int The ID for this payment. It must be stored in your "orders" table!
	 */
	public function requestSubscription(PaypalSubscriptionRequest $request) {
		$payment = $this->tdbmService->getNewObject("paypal_payments");

		// Payment is created in the "request" state.
		$payment->status_id = 1;

		$payment->paypal_business = $this->paypalConfig->businessAccount;
		//$payment->paypal_receiver_email
		//paypal_receiver_id
		//paypal_item_name
		//paypal_item_number
		//paypal_invoice
		$payment->paypal_option_name1 = $request->optionName0;
		$payment->paypal_option_selection1 = $request->optionSelection0;
		$payment->paypal_option_name2 = $request->optionName1;
		$payment->paypal_option_selection2 = $request->optionSelection1;
		//$payment->paypal_payment_status
		//$payment->paypal_pending_reason
		//$payment->paypal_reason_code
		//$payment->paypal_payment_date
		//$payment->paypal_txn_id
		//$payment->paypal_parent_txn_id
		//$payment->paypal_txn_type
		//$payment->paypal_mc_gross
		//$payment->paypal_mc_fee

		//??? doit-on sp�cifier mc_account1... plut�t que account1???
		$payment->paypal_mc_currency = $request->currencyCode;
		//$payment->paypal_settle_amount
		//$payment->paypal_settle_currency
		//$payment->paypal_exchange_rate
		//$payment->paypal_payment_gross
		//$payment->paypal_payment_fee
		$payment->paypal_first_name = $request->firstName;
		$payment->paypal_last_name = $request->lastName;
		//$payment->paypal_payer_business_name
		// TODO: how to map address1, address2?
		//$payment->paypal_address_name
		//$payment->paypal_address_street
		$payment->paypal_address_city = $request->city;
		$payment->paypal_address_state = $request->state;
		$payment->paypal_address_zip = $request->zip;
		$payment->paypal_address_country = $request->countryAddress;
		//$payment->paypal_address_status
		$payment->paypal_payer_email = $request->email;
		//$payment->paypal_payer_id
		//$payment->paypal_payer_status
		//$payment->paypal_payment_type
		//$payment->paypal_notify_version
		//$payment->paypal_erify_sign
		//$payment->paypal_ubscr_date
		//$payment->paypal_subscr_effective
		$payment->paypal_trial_period1 = $request->trialPeriod1;
		$payment->paypal_trial_period2 = $request->trialPeriod2;
		$payment->paypal_regular_period = $request->regularPeriod;
		$payment->paypal_trial_amount1 = $request->trialAmount1;
		$payment->paypal_trial_amount2 = $request->trialAmount2;
		$payment->paypal_regular_amount = $request->regularAmount;
		$payment->paypal_trial_period_unit1 = $request->trialPeriodUnit1;
		$payment->paypal_trial_period_unit2 = $request->trialPeriodUnit2;
		$payment->paypal_regular_period_unit = $request->regularPeriodUnit;
		//$payment->paypal_mc_amount1
		//$payment->paypal_mc_amount2
		//$payment->paypal_mc_amount3
		$payment->paypal_recurring = $request->recurringPayment;
		$payment->paypal_reattempt = $request->reattemptOnFailure;
		//$payment->paypal_retry_at
		$payment->paypal_recur_times = $request->recurringTimes;
		//$payment->paypal_username
		//$payment->paypal_password
		//$payment->paypal_subscr_id
		$payment->paypal_invoice = $request->invoice;
		$payment->paypal_tax = $request->tax;
		//$payment->save();
		// TODO: not really working. We should maybe remove paypal_custom.
		$payment->paypal_custom = $payment->id;

		$payment->save();

		// Now, let's perform the redirect.
		// For this, we will generate a simple HTML page that will print the form and automatically submit it.
		header("Content-Type: text/html;charset=".$this->paypalConfig->charset);
		include("redirect.php");

		return $payment->id;
	}
	
	/**
	* Redirects the user to the payment screen to request a subscription.
	* This method will also create a new subscription in database, with a status set to "request".
	* The subscription object will be passed in the "active" status if the payment is succesful.
	*
	* @param AbstractPaypalPaymentRequest $request
	* @return int The ID for this payment. It must be stored in your "orders" table!
	*/
	public function requestSimplePayment(PaypalSimplePaymentRequest $request) {
		$payment = $this->tdbmService->getNewObject("paypal_payments");
		/* @var $payment PaypalPayment */
		// Payment is created in the "request" state.
		$payment->status_id = 1;

		$payment->paypal_business = $this->paypalConfig->businessAccount;
		$payment->paypal_item_name = $request->itemName;
		$payment->paypal_mc_currency = $request->currencyCode;
		$payment->paypal_tax = $request->tax;
		$payment->amount = $request->amount;

		$payment->save();

		// Now, let's perform the redirect.
		// For this, we will generate a simple HTML page that will print the form and automatically submit it.
		header("Content-Type: text/html;charset=".$this->paypalConfig->charset);
		include("redirect.php");

		return $payment->id;
	}

	/**
	 * This will redirect the user to the Paypal website, on the subscription's choice page.
	 * From there, the user can select the subscription and cancel it.
	 * Paypal does not offer any other way to cancel a subscription.
	 *
	 * FIXME: not working properly: Paypal error message.
	 */
	public function requestUnsubscribe() {
		header("Location: ".$this->paypalConfig->paypalUrl."?cmd=_subscrfind&alias=".urlencode($this->paypalConfig->businessAccount));
	}

	/**
	 * Redirects the user to the payment screen to modify a subscription.
	 * This method will also modify the subscription bound to the order whose ID is $orderId.
	 * The subscription object will be passed in the "active" status if the payment is succesful.
	 *
	 * @param AbstractPaypalPaymentRequest $request
	 * @param string orderId The ID of the order the user will pay for
	 */
	public function modifySubscription(PaypalSubscriptionRequest $request, $paymentId) {
		// TODO
	}

	/**
	 * Returns the HTML form code.
	 *
	 * @param AbstractPaypalPaymentRequest $abstractRequest
	 */
	private function generateForm(AbstractPaypalPaymentRequest $request, $payment_id) {

		$html = '<form action="'.$this->paypalConfig->paypalUrl.'" method="POST">';
		$argsConfig = $this->paypalConfig->getPaypalArgsArray($this);
		$argsRequest = $request->getPaypalArgsArray();

		$args = array_merge($argsConfig, $argsRequest);
		$args["custom"] = $payment_id;
		foreach ($args as $key=>$value) {
			if ($value !== null) {
				$html .= "\n<input type=\"hidden\" name=\"$key\" value=\"".plainstring_to_htmlprotected($value)."\" />";
			}
		}
		$html .= "\n</form>";
		return $html;
	}

	/**
	 * This is the IPN.
	 * The controller responsible for the IPN should redirect to this function.
	 *
	 */
	public function ipn() {
		error_log(var_export($_POST, true));
		$this->log->trace("Entering IPN.");
		
		// We received an IPN request, let's add a log in the IPN database:


		// Let's start by checking if that transaction was processed before...
		$alreadyProcessed = $this->hasTransactionBeenProcessedBefore();

		$ipnLog = $this->tdbmService->getNewObject("paypal_ipn_responses");

		// First, write the full_request field.
		$requestArr = array();
		$postArr = array();
		foreach($_POST as $i => $v) {
			$requestArr[] = $i.'='.urlencode(get($i));
			$postArr[$i] = get($i);
		}
		$ipnLog->full_request = implode("&", $requestArr);

		foreach (self::$ipn_vars as $ipn_var) {
			$dbColumn = "paypal_".$ipn_var;
			if (isset($_POST[$ipn_var])) {
				$ipnLog->$dbColumn = get($ipn_var);
			}
			$requestArr[] = $ipn_var."=".urlencode(get($ipn_var));
			unset($postArr[$ipn_var]);
		}

		// The $postArr array contains all the arguments that have been sent by Paypal but that we did not log in the table (except in the full_request column).
		// Let's log all this as warnings:
		foreach ($postArr as $key=>$value) {
			$this->log->warn("Warning: during IPN, this argument was not stored into one column: ".$key."=".$value);
		}


		$this->log->debug("IPN message received with parameters: ".$ipnLog->full_request);

		// Now, let's check if the request is valid or not by calling Paypal
		$result = $this->checkIpnValidity();

		if (!$result) {
			// The request is not valid, let's mark it as an hacking attempt, and let's quit.
			$ipnLog->hackattempt = 1;
			return;
		}

		// Ok, this is not an hacking attempt.
		$ipnLog->hackattempt = 0;
		$ipnLog->save();

		// If the transaction was already processed, let's stop here:
		if ($alreadyProcessed)
			return;

		// Now, let's make sure that the IPN call contains what we sent
		// (let's make sure the user did not modify the form sent to Paypal)


		$paypalIpnResponse = $this->buildPaypalIpnResponseFromDbObject($ipnLog);

		$result = $this->checkIpnInputAndCallEventHandlers($paypalIpnResponse);
		if ($result == false) {
			return;
		}

	}


	/**
	 * This function performs a call to Paypal. It will return true if the IPN call is validated by Paypal.
	 * It will return false if Paypal does not validate the call.
	 * In this case, we should absolutely log the error and process it later as it might be a hacking attempt!
	 *
	 * @return boolean
	 */
	private function checkIpnValidity() {
		$url = $this->paypalConfig->paypalUrl;
		$postdata = '';
		foreach($_POST as $i => $v) {
			$postdata .= $i.'='.urlencode(get($i)).'&';
		}
		$postdata .= 'cmd=_notify-validate';

		$web = parse_url($url);
		if ($web['scheme'] == 'https') {
			$web['port'] = 443;
			$ssl = 'ssl://';
		} else {
			$web['port'] = 80;
			$ssl = '';
		}
		$fp = @fsockopen($ssl.$web['host'], $web['port'], $errnum, $errstr, 30);

		if (!$fp) {
			echo $errnum.': '.$errstr;
		} else {
			fputs($fp, "POST ".$web['path']." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$web['host']."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postdata . "\r\n\r\n");

			while(!feof($fp)) {
				$info[] = @fgets($fp, 1024);
			}
			fclose($fp);
			$info = implode(',', $info);
			if (eregi('VERIFIED', $info)) {
				return true;
			} else {
				$this->log->error("Warning! An error occured while verifying the IPN. This might be a hacking attempt! Message received: ".$info);
				return false;
			}
		}
	}

	/**
	 * Performs a call to the database to see if the transaction already exists or not.
	 * Returns true if a transasction with the same txn_id and that is not a hacking attemp was successful.
	 */
	private function hasTransactionBeenProcessedBefore() {
		$txn_id = get("txn_id");
		// Txn_id can be null sometimes (for instance for a subscription signup...)
		if ($txn_id != null) {
			$ipn_response = $this->tdbmService->getObjects("paypal_ipn_responses", array(new TDBM_EqualFilter("paypal_ipn_responses", "hackattempt", 0), new TDBM_EqualFilter("paypal_ipn_responses", "paypal_txn_id", $txn_id)));
			if (count($ipn_response) != 0) {
				// The transaction was already processed.
				$this->log->warn("Received a transaction from IPN with txn_id ".get("txn_id").". We already processed that transaction. It will be ignored.");
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns a PaypalIpnResponse object from the DB object.
	 *
	 * @param TDBM_Object $dbIpn
	 * @return PaypalIpnResponse
	 */
	private function buildPaypalIpnResponseFromDbObject(TDBM_Object $dbIpn) {
		$ipnResponse = new PaypalIpnResponse();
		foreach (self::$ipn_vars as $ipn_var) {
			$dbColumn = "paypal_".$ipn_var;
			$ipnResponse->$ipn_var = $dbIpn->$dbColumn;
		}
		return $ipnResponse;
	}

	/**
	 * Checks that the IPN response we receive corresponds to the Payment request we performed, and calls the event handlers if the check was successfull.
	 *
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function checkIpnInputAndCallEventHandlers(PaypalIpnResponse $paypalIpnResponse) {
		// First step: can we find the matching payment in the table?
		try {
			$payment = $this->tdbmService->getObject("paypal_payments", $paypalIpnResponse->custom);
		} catch (TDBM_Exception $e) {
			$this->log->info("Unable to find paypal_payments with id ".$paypalIpnResponse->custom, $e);
			$this->log->info("Entering onPaymentNotFound event handlers");
			// We cannot find a payment matching the IPN request!
			// So we probably received some money, but we cannot bind it to a payment.
			foreach ($this->eventHandlers as $eventHandler) {
				try {
					$eventHandler->onPaymentNotFound($paypalIpnResponse);
				} catch (Exception $e) {
					// Let's log the errors in the handlers and continue
					$this->log->error("An exception was thrown while calling a onPaymentNotFound method: ".get_class($e).". Message:".$e->getMessage());
				}
			}
			return false;
		}
		
//		if (count($payment) == 0) {
//			$this->log->info("Entering onPaymentNotFound event handlers");
//			// We cannot find a payment matching the IPN request!
//			// So we probably received some money, but we cannot bind it to a payment.
//			foreach ($this->eventHandlers as $eventHandler) {
//				try {
//					$eventHandler->onPaymentNotFound($paypalIpnResponse);
//				} catch (Exception $e) {
//					// Let's log the errors in the handlers and continue
//					$this->log->error("An exception was thrown while calling a onPaymentNotFound method: ".get_class($e).". Message:".$e->getMessage());
//				}
//			}
//			return false;
//		}
//
//		if (count($payment) > 1) {
//			$msg = "There is more than one payment that has the ID ".$paypalIpnResponse->custom."! This should never happen! The adminsitrator should investigate this problem!";
//			foreach ($this->eventHandlers as $eventHandler) {
//				try {
//					$eventHandler->onUndefinedError($msg);
//				} catch (Exception $e) {
//					// Let's log the errors in the handlers and continue
//					$this->log->error("An exception was thrown while calling a onUndefinedError method: ".get_class($e).". Message:".$e->getMessage());
//				}
//					
//			}
//			return false;
//		}
//
//		$payment = $payment[0];

		// We successfully matched the IPN to a payment in database.
		// Now, let's compare the IPN and the database value, and let's see if wa have a match.
		if ($paypalIpnResponse->txn_type == "subscr_signup" || $paypalIpnResponse->txn_type == "subscr_modify") {
			$basicCompareArray = array(
				"paypal_business"=>"business",
				"paypal_mc_currency"=>"mc_currency",
				"paypal_recurring"=>"recurring",
				"paypal_reattempt"=>"reattempt"
			);

			$numberCompareArray = array(
				"paypal_trial_amount1"=>"mc_amount1",
				"paypal_trial_amount2"=>"mc_amount2",
				"paypal_regular_amount"=>"mc_amount3"
			);

			// The paypal_custom number is not necessarily the same for a modify!
			// Indeed, we don't know for sure if the user will not modify another subscription (if the user has several subscriptions)
			// Using "modify" should in fact be avoided if possible!
			// Actually, from PaypalService 2.0, paypal_custom is no more filled.
			// Since paypal_custom = id, there is no need to fill it anyway.
			/*if ($paypalIpnResponse->txn_type == "subscr_signup") {
				$basicCompareArray["paypal_custom"] = "custom";
			}*/
			
			$error_fields = array();

			foreach ($basicCompareArray as $paymentKey => $ipnKey) {
				if ($payment->$paymentKey !== $paypalIpnResponse->$ipnKey) {
					$error_fields[] = $paymentKey;
				}
			}

			foreach ($numberCompareArray as $paymentKey => $ipnKey) {
				if ($payment->$paymentKey != $paypalIpnResponse->$ipnKey) {
					$error_fields[] = $paymentKey;
				}
			}

			if ($payment->paypal_trial_period1 != null || $payment->paypal_trial_period_unit1 != null || $paypalIpnResponse->period1 != null) {
				if ($payment->paypal_trial_period1." ".$payment->paypal_trial_period_unit1 != $paypalIpnResponse->period1) {
					$error_fields[] = "period1";
				}
			}
			if ($payment->paypal_trial_period2 != null || $payment->paypal_trial_period_unit2 != null || $paypalIpnResponse->period2 != null) {
				if ($payment->paypal_trial_period2." ".$payment->paypal_trial_period_unit2 != $paypalIpnResponse->period2) {
					$error_fields[] = "period2";
				}
			}
			if ($payment->paypal_regular_period." ".$payment->paypal_regular_period_unit != $paypalIpnResponse->period3) {
				$error_fields[] = "period3";
			}

			if (count($error_fields)>0) {
				//error_log("IPN   ".var_export($paypalIpnResponse, true));
				//error_log("Database   ".var_export($payment, true));
				$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);
				foreach ($this->eventHandlers as $eventHandler) {
					try {
						$eventHandler->onPaymentNotMatching($paypalIpnResponse, $paypalPayment, $error_fields);
					} catch (Exception $e) {
						// Let's log the errors in the handlers and continue
						$this->log->error("An exception was thrown while calling a onPaymentNotMatching method: ".get_class($e).". Message:".$e->getMessage());
					}
				}
				return false;
			}

			// Everything is alright. Cool!
			// Let's call the event handlers!
			if ($paypalIpnResponse->txn_type == "subscr_signup") {
				$this->handleSubscriptionSignup($payment, $paypalIpnResponse);
			} else {
				$this->handleSubscriptionModify($payment, $paypalIpnResponse);
			}
		} elseif ($paypalIpnResponse->txn_type == "subscr_payment") {
			// A payment for a subscription occured.
			// Let's ignore checks in this case since it is sent only by Paypal and does not come from a user form.

			// Let's update the next estimated payment date.
			// FIXME: may not work if there are trial periods

			/* The strptime function does not exist in Windows. In Windows, let's ignore this part */
			if (function_exists("strptime")) {
				$strPaymentDate = $paypalIpnResponse->payment_date;
				$paymentDateArray = strptime($strPaymentDate, "%H:%M:%S %b %d, %Y %Z");

				if ($paymentDateArray == false) {
					$this->log->error("Unable to parse the payment date '$strPaymentDate' returned by Paypal. The estimated EOT will not be computed.");
				} else {
					$time = mktime($paymentDateArray['tm_hour'],
							$paymentDateArray['tm_min'],
							$paymentDateArray['tm_sec'],
							$paymentDateArray['tm_mon']+1,
							$paymentDateArray['tm_mday'],
							$paymentDateArray['tm_year']);

					// Now, let's add the increment to reach the next payment:
					if ($payment->paypal_regular_period_unit == 'D') {
						$increment = 3600*24;
					} else if ($payment->paypal_regular_period_unit == 'M') {
						// FIXME: very rough approximation of what a month is...
						$increment = 3600*24*30;
					} else if ($payment->paypal_regular_period_unit == 'Y') {
						// FIXME: this ignore bissextiles years
						$increment = 3600*24*365;
					} else {
						$this->log->error("Unable to get the next payment date: unknown unit: ".$payment->paypal_regular_period_unit);
					}
					$nextTimeStamp = $time + $increment*$payment->paypal_regular_period;

					$mysqlDate = strftime("%Y-%m-%d %H:%M:%S", $nextTimeStamp);

					$payment->estimated_next_payment = $mysqlDate;

				}
			}

			/*$paymentDate = DateTime::createFromFormat("H:i:s M d, Y T", $strPaymentDate);
			$nextPaymentDate =
			$payment->estimated_next_payment = $paymentDate->format("Y-m-d H:i:s");
			*/


			$this->handleSubscriptionPayment($payment, $paypalIpnResponse);
		} elseif ($paypalIpnResponse->txn_type == "subscr_failed") {
			// A subscription failed.
			// Let's ignore checks in this case, the subscription failed anyway.

			$this->handleSubscriptionFailed($payment, $paypalIpnResponse);
		} elseif ($paypalIpnResponse->txn_type == "subscr_cancel") {
			// Someone cancels a subscription
			// Let's ignore checks in this case too. The user cannot lie about its account because he will be required to log-in to Paypal.
			$this->handleSubscriptionCancelled($payment, $paypalIpnResponse);
		} elseif ($paypalIpnResponse->txn_type == "subscr_eot") {
			// A subscription failed reached and of term.
			// Let's ignore checks in this case since it is sent only by Paypal and does not come from a user form.
		} elseif ($paypalIpnResponse->txn_type == "web_accept") {
			// A payment has been made
			$this->handleSimplePayment($payment, $paypalIpnResponse);
		} else {
			$this->log->error("Checking for IPN type '".$paypalIpnResponse->txn_type."' not implemented yet!");
		}

		return true;
	}

	/**
	 * Changes the payment record to set it to active, and calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionSignup(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {
		// Set status to 2: subscribed
		$payment->status_id = 2;

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onSubscriptionSignUp($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onSubscriptionSignUp method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Changes the payment record to set it to modified, and calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionModify(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {
		$this->log->error("Modify is not handled correctly by the PaypalService!!!! It will be treated like a normal payment.");

		// Set status to 2: subscribed
		$payment->status_id = 2;

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onModify($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onModify method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionPayment(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onSubscriptionPayment($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onSubscriptionPayment method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Changes the payment record to set it to failed, and calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionFailed(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {
		// Set status to 3: failed
		$payment->status_id = 3;

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onSubscriptionFailed($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onSubscriptionFailed method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Changes the payment record to set it to cancelled, and calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionCancelled(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {
		// Set status to 4: cancelled
		$payment->status_id = 4;

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onSubscriptionCancelled($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onSubscriptionCancelled method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Changes the payment record to set it to EOT, and calls the event handlers.
	 *
	 * @param TDBM_Object $payment
	 * @param PaypalIpnResponse $paypalIpnResponse
	 */
	private function handleSubscriptionEndOfTerm(TDBM_Object $payment, PaypalIpnResponse $paypalIpnResponse) {
		// Set status to 5: cancelled
		$payment->status_id = 5;

		$paypalPayment = $this->getPaypalPaymentFromDbObject($payment);

		// Let's call the event handlers
		foreach ($this->eventHandlers as $eventHandler) {
			try {
				$eventHandler->onSubscriptionEndOfTerm($paypalPayment, $paypalIpnResponse);
			} catch (Exception $e) {
				// Let's log the errors in the handlers and continue
				$this->log->error("An exception was thrown while calling a onSubscriptionEndOfTerm method: ".get_class($e).". Message:".$e->getMessage());
			}
		}
	}

	/**
	 * Returns a PaypalPayment initialized from a TDBM_Object row of the table 'paypal_payments'.
	 *
	 * @param TDBM_Object $dbObj
	 * @return PaypalPayment
	 */
	private function getPaypalPaymentFromDbObject(TDBM_Object $dbObj) {
		$paypalPayment = new PaypalPayment();

		foreach (get_class_vars("PaypalPayment") as $key=>$value) {
			$paypalPayment->$key = $dbObj->$key;
		}
		return $paypalPayment;
	}

	//TODO
	//private function estimateNextPaymentDate
}
?>