<?php
require_once 'DataColumnFormatterInterface.php';
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
	
	/**
	 * Linnk's label
	 *
	 * @Property
	 * @var string
	 */
	public $label;
	
	/**
	 * If passed, this will display the given image in place of the label
	 *
	 * @Property
	 * @var string
	 */
	public $image;
	
	/**
	 * If passed, this will add the given javascript as "onclick" event
	 *
	 * @Property
	 * @var string
	 */
	public $onclick;
	
	public function __construct($baseLinkUrl, $label, $idName=null, $addParam=null,  $image=null, $onclick=null) {
		$this->baseLinkUrl = $baseLinkUrl;
		$this->label = $label;
		$this->addParam = $addParam;
		$this->idName = !$idName?"id":$idName;
		$this->image = $image;
		$this->onclick = $onclick;
	}
	
	public function format($value){
		$inner = $this->image?'<img src="'.IMAGES_URL.$this->image.'" title="" alt="'.IMAGES_URL.$this->image.'">':$this->label;
		$onclick = $this->onclick?"onclick='$this->onclick'":"";
		return '<a href="'.$this->baseLinkUrl.'?'.$this->idName.'='.$value.$this->addParam.'" '.$onclick.'>'.$inner.'</a>';
	}	
}
?>