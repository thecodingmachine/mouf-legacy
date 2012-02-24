<?php

/**
 * This interface is implemented by all form renderers
 * @Component
 */
interface BCERenderer{
	
	/**
	 * Main function of the Renderer: output the form's HTML
	 * @param array<FieldDescriptorInterface> $fieldDescriptors
	 */
	public function render(BCEForm $fieldDescriptors);
	
}