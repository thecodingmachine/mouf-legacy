<?php

/**
 * This class contains utility functions to display currencies.
 * 
 * @author David Negrier
 */
class FineCurrencyUtils {
	private static $currencySymbols = array(
		'AED'=>'',
		'AFN'=>'',
		'ALL'=>'',
		'AMD'=>'',
		'ANG'=>'NAƒ',
		'AOA'=>'',
		'ARS'=>'',
		'AUD'=>'$',
		'AWG'=>'ƒ',
		'AZN'=>'',
		'BAM'=>'KM',
		'BBD'=>'Bds$',
		'BDT'=>'',
		'BGN'=>'',
		'BHD'=>'',
		'BIF'=>'FBu',
		'BMD'=>'BD$',
		'BND'=>'B$',
		'BOB'=>'Bs.',
		'BRL'=>'R$',
		'BSD'=>'B$',
		'BTN'=>'Nu.',
		'BWP'=>'P',
		'BYR'=>'Br',
		'BZD'=>'BZ$',
		'CAD'=>'$',
		'CDF'=>'F',
		'CHF'=>'Fr.',
		'CLP'=>'$',
		'CNY'=>'¥',
		'COP'=>'Col$',
		'CRC'=>'₡',
		'CUC'=>'$',
		'CVE'=>'Esc',
		'CZK'=>'Kč',
		'DJF'=>'Fdj',
		'DKK'=>'Kr',
		'DOP'=>'RD$',
		'DZD'=>'',
		'EEK'=>'KR',
		'EGP'=>'£',
		'ERN'=>'Nfa',
		'ETB'=>'Br',
		'EUR'=>'€',
		'FJD'=>'FJ$',
		'FKP'=>'£',
		'GBP'=>'£',
		'GEL'=>'',
		'GHS'=>'',
		'GIP'=>'£',
		'GMD'=>'D',
		'GNF'=>'FG',
		'GQE'=>'CFA',
		'GTQ'=>'Q',
		'GYD'=>'GY$',
		'HKD'=>'HK$',
		'HNL'=>'L',
		'HRK'=>'kn',
		'HTG'=>'G',
		'HUF'=>'Ft',
		'IDR'=>'Rp',
		'ILS'=>'',
		'INR'=>'',
		'IQD'=>'',
		'IRR'=>'',
		'ISK'=>'kr',
		'JMD'=>'J$',
		'JOD'=>'',
		'JPY'=>'¥',
		'KES'=>'KSh',
		'KGS'=>'',
		'KHR'=>'',
		'KMF'=>'',
		'KPW'=>'W',
		'KWD'=>'',
		'KYD'=>'KY$',
		'KZT'=>'T',
		'LAK'=>'KN',
		'LBP'=>'',
		'LKR'=>'Rs',
		'LRD'=>'L$',
		'LSL'=>'M',
		'LTL'=>'Lt',
		'LVL'=>'Ls',
		'LYD'=>'LD',
		'MAD'=>'',
		'MDL'=>'',
		'MGA'=>'FMG',
		'MKD'=>'',
		'MMK'=>'K',
		'MNT'=>'₮',
		'MOP'=>'P',
		'MRO'=>'UM',
		'MUR'=>'Rs',
		'MVR'=>'Rf',
		'MWK'=>'MK',
		'MXN'=>'$',
		'MYR'=>'RM',
		'MZM'=>'MTn',
		'NAD'=>'N$',
		'NGN'=>'₦',
		'NIO'=>'C$',
		'NOK'=>'kr',
		'NPR'=>'NRs',
		'NZD'=>'NZ$',
		'OMR'=>'',
		'PAB'=>'B./',
		'PEN'=>'S/.',
		'PGK'=>'K',
		'PHP'=>'₱',
		'PKR'=>'Rs.',
		'PLN'=>'',
		'PYG'=>'',
		'QAR'=>'QR',
		'RON'=>'L',
		'RSD'=>'din.',
		'RUB'=>'R',
		'RWF'=>'RF',
		'SAR'=>'SR',
		'SBD'=>'SI$',
		'SCR'=>'SR',
		'SDG'=>'',
		'SEK'=>'kr',
		'SGD'=>'S$',
		'SHP'=>'£',
		'SLL'=>'Le',
		'SOS'=>'Sh.',
		'SRD'=>'$',
		'STD'=>'Db',
		'SYP'=>'',
		'SZL'=>'E',
		'THB'=>'฿',
		'TJS'=>'',
		'TMT'=>'m',
		'TND'=>'DT',
		'TRY'=>'YTL',
		'TTD'=>'TT$',
		'TWD'=>'NT$',
		'TZS'=>'',
		'UAH'=>'',
		'UGX'=>'USh',
		'USD'=>'$',
		'UYU'=>'$U',
		'UZS'=>'',
		'VEB'=>'Bs',
		'VND'=>'₫',
		'VUV'=>'VT',
		'WST'=>'WS$',
		'XAF'=>'CFA',
		'XCD'=>'EC$',
		'XDR'=>'SDR',
		'XOF'=>'CFA',
		'XPF'=>'F',
		'YER'=>'',
		'ZAR'=>'R',
		'ZMK'=>'ZK',
		'ZWR'=>'Z$'
		);
	
	/**
	 * Returns the currency symbol based on the currency ISO 4217 code.
	 * 
	 * @param string $isocode
	 */
	public static function getCurrencySymbol($isocode) {
		return empty(self::$currencySymbols[$isocode])?$isocode:self::$currencySymbols[$isocode];
	}
}