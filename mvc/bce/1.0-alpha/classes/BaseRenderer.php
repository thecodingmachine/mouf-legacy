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
	
	
	public function render($fieldDescriptors){
?>
	<form action="<?php echo $this->action; ?>" method="<?php echo $this->method?>" name="<?php echo $this->name;?>" id="<?php echo $this->id ?>">
		<?php
		foreach ($fieldDescriptors as $descriptor) {
			/* @var $descriptor FieldDescriptorInterface */
			$renderer = $descriptor->getRenderer();
			?>	
			<div>
				<label><?php echo $descriptor->getFieldLabel() ?></label>
				<?php echo $renderer->render($descriptor); ?>
			</div>
			<?php
		}
		?>
	<button type="submit">Submit</button>
	</form>
<?php
	}
	
}