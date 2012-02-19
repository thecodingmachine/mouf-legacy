<?php
require_once 'BCERenderer.php';

/**
 * This is a simple form rendering class, using a simple field layout :
 * 	<div>
 * 		<label></label>
 * 		[field HTML]
 *	</div>
 * Enter description here ...
 * @Component
 * @author Kevin
 *
 */
class BaseRenderer implements BCERenderer{
	
	/**
	 * Enter description here ...
	 * @Property
	 * @var string 
	 */
	public $action = "save";
	
	/**
	 * Enter description here ...
	 * @Property
	 * @var string 
	 */
	public $method = "POST";
	
	/**
	* Enter description here ...
	* @Property
	* @var string
	*/
	public $name = "default_form";
	
	/**
	* Enter description here ...
	* @Property
	* @var string
	*/
	public $id = "default_id";
	
	
	public function init($fieldDescriptors){
		foreach ($fieldDescriptors as $descriptor) {
			/* @var $descriptor FieldDescriptorInterface */
			$renderer = $descriptor->getRenderer();
			$html = $renderer->render($descriptor);
			$formHtml .= "
				<div>
					<label>$descriptor->label</label>
					$html
				</div>
			";
			$fieldName = $descriptor->getFieldName();
			$validator = $descriptor->getValidator();
			$ruleObj = new stdClass();
			foreach ($validator->getJsRules as $key => $rule) {
				$ruleObj->$key = $rule;
			}
		}
?>
	<form action="<?php echo $this->action; ?>" method="<?php echo $this->method?>" name="<?php echo $this->name;?>" id="<?php echo $this->id ?>">
		<?php echo $formHtml ?>
	<button type="submit">Submit</button>
	</form>
<?php
	}
	
}