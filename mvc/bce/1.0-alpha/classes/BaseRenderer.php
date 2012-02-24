<?php
require_once 'BCERenderer.php';

/**
 * This is a simple form rendering class, using a simple field layout :
 * 	<div>
 * 		<label></label>
 * 		<input .../>
 *	</div>
 *
 * @Component
 *
 */
class BaseRenderer implements BCERenderer{
	
	public function render(BCEForm $form){
		$fieldDescriptors = $form->fieldDescriptors;
?>
	<form action="<?php echo $form->action; ?>" method="<?php echo $form->method?>" name="<?php echo $form->name;?>" id="<?php echo $form->id ?>">
		<?php
		$idDescriptor = $form->idFieldValidator;
		$idRenderer = $idDescriptor->getRenderer();
		echo $idRenderer->render($idDescriptor);
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
	<input type="submit" value="Submit" class="submit">
	</form>
<?php
	}
	
}