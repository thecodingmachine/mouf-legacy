<?php
/**
 * The link formatter is used to add links to datagrid columns.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class LinkFormatter implements DataColumnFormatterInterface {

	/**
	 * The base link URL that the link will lead to.
	 * This is relative to the root of the web application, and it should not start with a /
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $baseLinkUrl;
	
	/**
	 * If passed, this string is added after the link.
	 * For instance, if you pass "&amp;action=edit", your link will be:
	 *  http://server/baseLinkUrl?id=[id]&amp;action=edit
	 * 
	 * @Property
	 * @var string
	 */
	public $addParam;

	/**
	 * If passed, this will replace the default "id" parameter with your parameter.
	 *
	 * @Property
	 * @var string
	 */
	public $idName;
	
	public function __construct($baseLinkUrl=null, $addParam=null, $idName=null) {
		$this->baseLinkUrl = $baseLinkUrl;
		$this->addParam = $addParam;
		$this->idName = $idName;
	}
}
?>