<?php
require_once '/../BCERendererInterface.php';

/**
 * This is a simple form rendering class, using a simple field layout :
 *
 * @Component
 *
 */
class BaseRenderer implements BCERendererInterface{
	
	/**
	 * @Property
	 * @var WebLibrary
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
			?>	
			<div class="control-group">
				<label for="input01" class="control-label"><?php echo $descriptor->getFieldLabel() ?></label>
				<div class="controls">
					<?php echo $descriptor->toHtml(); ?>
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
	
	public function getSkin(){
		return $this->skin;
	}
}