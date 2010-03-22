<?php
/**
 * An object representing the response sent by the Paypal IPN to the application.
 *
 */
class PaypalIpnResponse {
	public $item_name;
	public $business;
	public $item_number;
	public $payment_status;
	public $mc_gross;
	public $mc_currency;
	public $txn_id;
	public $receiver_email;
	public $receiver_id;
	public $quantity;
	public $num_cart_items;
	public $payment_date;
	public $first_name;
	public $last_name;
	public $payment_type;
	public $payment_gross;
	public $payment_fee;
	public $settle_amount;
	public $memo;
	public $payer_email;
	public $txn_type;
	public $payer_status;
	public $address_street;
	public $address_city;
	public $address_state;
	public $address_zip;
	public $address_country;
	public $address_status;
	public $tax;
	public $option_name1;
	public $option_selection1;
	public $option_name2;
	public $option_selection2;
	public $invoice;
	public $custom;
	public $notify_version;
	public $verify_sign;
	public $payer_business_name;
	public $payer_id;
	public $mc_fee;
	public $exchange_rate;
	public $settle_currency;
	public $parent_txn_id;
	public $pending_reason;
	public $reason_code;
	public $residence_country;
	public $test_ipn;
	public $charset;
	public $subscr_id;
	public $subscr_date;
	public $subscr_effective;
	public $period1;
	public $period2;
	public $period3;
	public $amount1;
	public $amount2;
	public $amount3;
	public $mc_amount1;
	public $mc_amount2;
	public $mc_amount3;
	public $recurring;
	public $reattempt;
	public $retry_at;
	public $recur_times;
	public $username;
	public $password;
	public $for_auction;
	public $auction_closing_date;
	public $auction_multi_item;
	public $auction_buyer_id;

}
?>