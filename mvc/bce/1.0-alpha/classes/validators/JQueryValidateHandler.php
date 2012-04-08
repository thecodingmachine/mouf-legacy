<?php
/**
 * Builds the validation script of a form depending on it's field descriptors and their validators using the jQuery Validate syntax and library
 * 
 * @Component
 * @author Kevin
 *
 */
class JQueryValidateHandler implements JsValidationHandlerInterface{
	
	/**
	 * Contains all the validation functions
	 */
	private $validationMethods;
	
	/**
	 * Contains all the rule to be applied, field by field
	 */
	private $validationRules;
	
	private function wrapRule(FieldDescriptor $fieldDescriptor, JsValidatorInterface $validator, $ruleIndex){
		return "
			$.validator.addMethod(
				'".$fieldDescriptor->getFieldName()."_rule_$ruleIndex',
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
	public function buildValidationScript(FieldDescriptor $descriptor, $formId){
		$i = 0;
		$fieldName = $descriptor->getFieldName();
		$validators = $descriptor->getValidators();
		
		foreach ($validators as $validator) {
			if ($validator instanceof JsValidatorInterface) {
				$this->validationMethods[] = $this->wrapRule($descriptor, $validator, $i);
				$methodName = $fieldName."_rule_".$i;
				$this->validationRules->rules->$fieldName->$methodName = $validator->getJsArguments();
				$i++;
			}
		}
	}
	
	public function getValidationJs($formId){
		$rulesJson = json_encode($this->validationRules);
		
		$js = '
		$(document).ready(function(){
			_currentForm =document.getElementById("'.$formId.'");
			
			_checkable =  function( element ) {
				return /radio|checkbox/i.test(element.type);
			};
			
			_findByName = function( name ) {
				// select by name and filter by form for performance over form.find("[name=...]")
				var form = _currentForm;
				return $(document.getElementsByName(name)).map(function(index, element) {
					return element.form == form && element.name == name && element  || null;
				});
			};
			
			_getLength = function(value, element) {
				switch( element.nodeName.toLowerCase() ) {
				case "select":
					return $("option:selected", element).length;
				case "input":
					if( _checkable( element) )
						return _findByName(element.name).filter(":checked").length;
				}
				return value.length;
			};
		
			_depend = function(param, element) {
				return _dependTypes[typeof param]
					? _dependTypes[typeof param](param, element)
					: true;
			};
		
			_dependTypes = {
				"boolean": function(param, element) {
					return param;
				},
				"string": function(param, element) {
					return !!$(param, element.form).length;
				},
				"function": function(param, element) {
					return param(element);
				}
			},
		';
		
		foreach ($this->validationMethods as $method) {
			$js.="
				$method			
			";
		}
		$js.= "
		
			$('#$formId').validate($rulesJson);
		});";
		
		
		return $js;
	}
	
}