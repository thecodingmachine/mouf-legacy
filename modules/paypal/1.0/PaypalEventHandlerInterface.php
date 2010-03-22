<?php

/**
 * This is an interface that should be implemented by the class responsible for handling payments or subscription events sent by Paypal.
 * This interface manages events (subscription, payment, subscrition cancelled, hacking attempt, errors, etc...)
 * There are a lot of methods in this interface, but you do not have to provide behaviour for all those methods.
 * Some can safely be ignored.
 */
interface PaypalEventHandlerInterface {
	
	
	/**
	 * A payment was received from the IPN but we cannot find the matching payment in the database.
	 * This can happen if there was some trouble with the Payments database.
	 * By implementing this method, you can act upon such problems (for instance by sending a mail to the adminsitrator, etc...)
	 *
	 * @param PaypalIpnResponse $ipnResponse The IPN response we could not bind to a payment.
	 */
	function onPaymentNotFound(PaypalIpnResponse $ipnResponse);
	
	/**
	 * A payment was received from the IPN but it does not match what we sent to Paypal.
	 * It is likely that the user tried to modify the form sent to Paypal.
	 * Therefore, we are potentially receiving payments for a product, but not the right value or not with the right parameters.
	 * The payment will be ignored, but it is processed by Paypal. Therefore, an administrator should solve the problem.
	 *
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal
	 * @param PaypalPayment $paypalPayment The Payment object that stores the initial request
	 * @param array $errorFields The list of fields that are in error (as an array of strings)
	 */
	function onPaymentNotMatching(PaypalIpnResponse $paypalIpnResponse, PaypalPayment $paypalPayment, $errorFields);
	
	/**
	 * An undefined error occured while processing an IPN message.
	 * This should result in the error being logged, and maybe a message sent to the administrator. 
	 *
	 * @param string $errorMsg
	 */
	function onUndefinedError($errorMsg);
	
	/**
	 * A subscription was signed-up.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onSubscriptionSignUp(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
	
	/**
	 * A subscription was modified.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onModify(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
	
	/**
	 * A payment was received for a subscription.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 * You should not use that payment to activate the signup. Instead, you should use the onSubscriptionSignUp for signup management.
	 * Be aware that for the first payment, you can receive onSubscriptionPayment before the call to onSubscriptionSignUp (the opposite
	 * is possible too). 
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onSubscriptionPayment(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
	
	/**
	 * A subscription attempt failed, or a payment for the service failed.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onSubscriptionFailed(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
	
	/**
	 * A subscription was cancelled by the user.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 * Please note that this message is received as soon as the user cancels the message. If the user already
	 * as still a few days/months to go with its current subscription, it will receive the End of term notification
	 * at the end of the subscription term.
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onSubscriptionCancelled(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
	
	/**
	 * A subscription arrives to End Of Term.
	 * You should use the ID of the $paypalPayment payment object to match the user that requested that payment.
	 * This mnotification should be used to remove access to the service.
	 *
	 * @param PaypalPayment $paypalPayment The Payment object that stores details about what was signed up.
	 * @param PaypalIpnResponse $paypalIpnResponse The IPN response received from Paypal (for information, you don't have to use it).
	 */
	function onSubscriptionEndOfTerm(PaypalPayment $paypalPayment, PaypalIpnResponse $paypalIpnResponse);
}
?>