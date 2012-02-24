<?php
/**
 * Builds the validation script of a form depending on it's field descriptors and their validators using the jQuery Validate syntax and library
 * 
 * @Component
 * @author Kevin
 *
 */
class JQueryValidateHandler implements JsValidationHandlerInterface{
	
	private function wrapRule(FieldDescriptorInterface $fieldDescriptor, JsValidatorInterface $validator, $ruleIndex){
		return "
			$.validator.addMethod(
				'".$fieldDescriptor->getFieldName()."_$ruleIndex',
				function(value, element) { 
					var functionCall = ".$validator->getScript()."
					return functionCall(value, element);
				},
				'".str_replace("{fieldName}", $fieldDescriptor->getFieldLabel(), $validator->getErrorMessage())."'
			);";
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidationHandlerInterface::buildValidationScript()
	 */
	public function buildValidationScript(FieldDescriptorInterface $descriptor, $formId){
		$i = 0;
		$fieldName = $descriptor->getFieldName();
		$validators = $descriptor->getValidators();
		
		foreach ($validators as $validator) {
			if ($validator instanceof JsValidatorInterface) {
				$validationJS[] = $this->wrapRule($descriptor, $validator, $i);
				$methodName = $fieldName."_".$i;
				$jsvalidationRules->rules->$fieldName->$methodName = $validator->getJsArguments();
				$i++;
			}
		}
		$rulesJson = json_encode($jsvalidationRules);
		$validationJS[] = "
		$(document).ready(function(){
			$('#$formId').validate($rulesJson);
		});";
		
		return $validationJS;
	}
	
}