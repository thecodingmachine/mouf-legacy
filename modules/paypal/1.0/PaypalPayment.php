<?php

/**
 * Represents a payment or a subscription that has previously been requested.
 *
 */
class PaypalPayment {
	public $id;
	public $status_id;
	public $paypal_business;
	public $paypal_receiver_email;
	public $paypal_receiver_id;
	public $paypal_item_name;
	public $paypal_item_number;
	public $paypal_invoice;
	public $paypal_custom;
	public $paypal_option_name1;
	public $paypal_option_selection1;
	public $paypal_option_name2;
	public $paypal_option_selection2;
	public $paypal_payment_status;
	public $paypal_pending_reason;
	public $paypal_reason_code;
	public $paypal_payment_date;
	public $paypal_txn_id;
	public $paypal_parent_txn_id;
	public $paypal_txn_type;
	public $paypal_mc_gross;
	public $paypal_mc_fee;
	public $paypal_mc_currency;
	public $paypal_settle_amount;
	public $paypal_settle_currency;
	public $paypal_exchange_rate;
	public $paypal_payment_gross;
	public $paypal_payment_fee;
	public $paypal_first_name;
	public $paypal_last_name;
	public $paypal_payer_business_name;
	public $paypal_address_name;
	public $paypal_address_street;
	public $paypal_address_city;
	public $paypal_address_state;
	public $paypal_address_zip;
	public $paypal_address_country;
	public $paypal_address_status;
	public $paypal_payer_email;
	public $paypal_payer_id;
	public $paypal_payer_status;
	public $paypal_payment_type;
	public $paypal_notify_version;
	public $paypal_verify_sign;
	public $paypal_ubscr_date;
	public $paypal_subscr_effective;
	public $paypal_trial_period1;
	public $paypal_trial_period2;
	public $paypal_regular_period;
	public $paypal_trial_amount1;
	public $paypal_trial_amount2;
	public $paypal_regular_amount;
	public $paypal_trial_period_unit1;
	public $paypal_trial_period_unit2;
	public $paypal_regular_period_unit;
	public $paypal_mc_amount1;
	public $paypal_mc_amount2;
	public $paypal_mc_amount3;
	public $paypal_recurring;
	public $paypal_reattempt;
	public $paypal_retry_at;
	public $paypal_recur_times;
	public $paypal_username;
	public $paypal_password;
	public $paypal_subscr_id;
	public $paypal_tax;
	public $estimated_next_payment;
	public $creation_date;
	public $creation_user_id;
	public $modification_date;
	public $modification_user_id;

}
?>