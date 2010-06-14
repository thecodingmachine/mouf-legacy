<?php

/**
 * A utility class specialized in parsing annotations.
 *
 */
class MoufAnnotationHelper {
	
	/**
	 * Returns a list from the string. The string can be specified as "toto", "tata", "titi"
	 *
	 * @param $value string
	 * @return array<string>
	 */
	public static function getValueAsList($value) {
		$tokens = token_get_all('<?php '.$value);

		$resultArray = array();
		
		// Le's find all the strings, and let's put each string in the table.
		foreach ($tokens as $token) {
			if ($token[0] == T_CONSTANT_ENCAPSED_STRING) {
				$resultArray[] = substr($token[1], 1, strlen($token[1])-2) ;
			}
		}

		return  $resultArray;
	}
}
?>