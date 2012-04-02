<?php
interface JsValidationHandlerInterface{
	
	/**
	 * Builds the validation script of a form depending on it's field descriptors and their validators
	 * A validator will have JS impacts only if
	 *   - it implements the JsValidatorInterface
	 *   or
	 *   - if the related PhpValidator (Server Side) has "PHP Fall Back" property activated
	 *   
	 * @param FieldDescriptorInterface $fieldDescriptor
	 * @param string $formId
	 */
	public function buildValidationScript(FieldDescriptorInterface $descriptor, $formId);

	/**
	 * Returns the Javascript Code that will handle from's validation: methods and rules
	 * @param $formId the html 'id' attribute of the form
	 */
	public function getValidationJs($formId);	
}