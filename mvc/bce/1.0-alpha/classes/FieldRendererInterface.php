<?php
/**
 * This interface is implemented by any field renderer
 *
 */
interface FieldRendererInterface{
	
	/**
	 * Main function of the FieldRenderer : return field's HTML code
	 * @param FieldDescriptorInterface $descriptor
	 * @return string
	 */
	public function render(FieldDescriptorInterface $descriptor);
	
}