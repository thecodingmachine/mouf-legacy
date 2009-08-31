<?php 
/**
 * Any class implementing this interface can be used 
 * @author Marc
 *
 */
interface EventInterface {
	/**
	 * Return the title
	 * @return string
	 */
	function getTitle();
	/**
	 * Return the start date in timestamp format
	 * @return int
	 */
	function getDateStart();
	/**
	 * Return the end date in timestamp format
	 * @return int
	 */
	function getDateEnd();
	/**
	 * Return the content
	 * @return string
	 */
	function getContent();
	/**
	 * Return the category
	 * @return CategoryInterface
	 */
	function getCategory();
	/**
	 * Return the link
	 * @return string
	 */
	function getLink();
	/**
	 * Return the event style
	 * @return string
	 */
	function getStyleEvent();
	/**
	 * Return the tooltip style
	 * @return string
	 */
	function getStyleTooltip();
}
?>