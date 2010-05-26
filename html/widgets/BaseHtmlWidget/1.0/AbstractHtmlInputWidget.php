<?php

/**
 * This class is a base class representing HTML widgets.
 *
 */
abstract class AbstractHtmlInputWidget implements HtmlElementInterface {
	
	/**
	 * The label of the widget.
	 *
	 * @Property
	 * @var string
	 */
	public $label;
	
	/**
	 * The id to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $id;
	
	/**
	 * The name attribute of the widget.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $name;

	/**
	 * The name of the CSS class to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $css;
	
	/**
	 * Whether the selected value should be read directly from the request or not.
	 *
	 * @Property
	 * @var bool
	 */
	public $selectDefaultFromRequest = true;
		
	/**
	 * Whether the field is required or not.
	 * This can be enforced on the client-side if you are using jQuery validate.
	 * 
	 * @Property
	 * @var bool
	 */
	public $required;
	
	
	/**
	 * Whether the label should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nLabel;
};