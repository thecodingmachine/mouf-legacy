<?php

/**
 * Utility class providing help to manage colors.
 * 
 * @author david
 */
class SimpleGraphUtils {
	
	/**
	 * Returns an array of 3 values from the hexadecimal color code.
	 * 
	 * @param $color
	 * @return array
	 */
	public static function colorHtmlToDecimal($color) {
		// Remove any trailing #.
		if (strpos($color, "#")===0) {
			$color = substr($color, 1);
		}
		
		if (strlen($color) != 6) {
			throw new Exception("The color format should be hexadecimal. For instance: #468743. Value passed: ".$color);
		}
		
		$primaryOne = substr($color,0,2);
		$primaryTwo = substr($color,2,2);
		$primaryThree = substr($color,4,2);
		
		$primaryOne = hexdec($primaryOne);
		$primaryTwo = hexdec($primaryTwo);
		$primaryThree = hexdec($primaryThree);
		
		return array($primaryOne, $primaryTwo, $primaryThree);
	}
	
	/**
	 * Returns an Artichow color object.
	 * 
	 * @param $color
	 * @return awColor
	 */
	public static function getAwColorFromHex($color) {		
		$arr = self::colorHtmlToDecimal($color);
		return new awColor($arr[0], $arr[1], $arr[2]);
	}
}
?>