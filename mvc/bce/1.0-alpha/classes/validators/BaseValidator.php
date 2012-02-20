<?php
/**
 * @Component
 * Enter description here ...
 * @author Kevin
 *
 */
abstract class BaseValidator implements ValidatorInterface{
	
	/**
	 * @Property
	 * @var bool $required
	 */
	public $required;

	/**
	 * @Property 
	 * @var array<string, string>
	 */
	public $jsRules;
	
	public function loadRules(){
		if ($this->required) $this->jsRules["required"]=true;
	}
	
	
	/**
	 * Returns the JS validation rules
	 * @return array<string, string>
	 */
	public function getJsRules(){
		return $this->jsRules;
	}
	
}