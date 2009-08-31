<?php 
/**
 * Any class implementing this interface can be used 
 * @author Marc
 *
 */
interface CategoryInterface {
	/**
	 * Return the name
	 * @return string
	 */
	function getName();
	
	/**
	 * Return the category style
	 * @return string
	 */
	function getStyleCategory();
	
	/**
	 * Return the tooltip style
	 * @return string
	 */
	function getStyleTooltip();
}
?>