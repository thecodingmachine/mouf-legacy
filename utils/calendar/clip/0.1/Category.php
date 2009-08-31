<?php 
/**
 * @author Marc Teyssier
 *
 */
class Category implements CategoryInterface {
	private $id;
	private $name;
	private $style_category;
	private $style_tooltip;
	
	/**
	 * Set the id
	 * @param $id mix You can use it to identify your category
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Set the name
	 * @param $name string
	 */
	function setName($name) {
		$this->name = $name;
	}
	/**
	 * Set a style for the category
	 * @param $style_category string
	 */
	function setStyleCategory($style_category) {
		$this->style_category = $style_category;
	}
	/**
	 * Set a style for the tooltip category
	 * @param $style_tooltip string
	 */
	function setStyleTooltip($style_tooltip) {
		$this->style_tooltip = $style_tooltip;
	}
	
	/**
	 * Return the id
	 * @return string
	 */
	function getId() {
		return $this->id;
	}
	/**
	 * Return the name
	 * @return string
	 */
	function getName() {
		return $this->name;
	}
	/**
	 * Return the category style
	 * @return string
	 */
	function getStyleCategory() {
		return $this->style_category;
	}
	/**
	 * Return the tooltip style
	 * @return string
	 */
	function getStyleTooltip() {
		return $this->style_tooltip;
	}
}
?>