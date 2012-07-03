<?php
require_once 'BCERenderer.php';

/**
 * This is a simple form rendering class, using a simple field layout :
 *
 * @Component
 *
 */
class BaseRenderer implements BCERenderer{
	
	/**
	 * @Property
	 * @var string
	 * @OneOf ("basic", "smart")
	 */
	public $skin;
	
	public function render(BCEForm $form){
		$fieldDescriptors = array_merge($form->fieldDescriptors, $form->many2ManyFieldDescriptors);
?>
	<form class="form-horizontal" action="<?php echo $form->action; ?>" method="<?php echo $form->method?>" name="<?php echo $form->name;?>" id="<?php echo $form->id ?>">
	<fieldset>
		<?php
		$idDescriptor = $form->idFieldDescriptor;
		$idRenderer = $idDescriptor->getRenderer();
		echo $idRenderer->render($idDescriptor);
		foreach ($fieldDescriptors as $descriptor) {
			/* @var $descriptor FieldDescriptor */
			$renderer = $descriptor->getRenderer();
			?>	
			<div class="control-group">
				<label for="input01" class="control-label"><?php echo $descriptor->getFieldLabel() ?></label>
				<div class="controls">
					<?php echo $renderer->render($descriptor); ?>
				</div>
			</div>
			<?php
		}
		?>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit">Save changes</button>
			<button class="btn">Cancel</button>
		</div>
	</fieldset>
	</form>
<?php
	}
	
	public function getDefaultStyleSheet(){
		switch ($this->skin) {
			case "basic":
				return "plugins/mvc/bce/1.0-alpha/css/basic.css";
			break;
			case "smart":
				return "plugins/mvc/bce/1.0-alpha/css/smart.css";
			;
			break;
		}
	}
	
}