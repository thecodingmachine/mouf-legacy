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
	* @var string $options
	*/
	public $options;
	
	/**
	 * @Property 
	 * @var array<string>
	 */
	public $jsRules;
	
// 	abstract public function validate($value);
	
	public function getHtmlAttribute(){
		if ($this->required) $this->jsRules["required"]="true";
	}
	
	/**
	 * @param array<string, string> $options
	 */
	public function setJsRules($options){
		$this->options = $options;
	}
	
	/**
	 * Returns the JS validation rules
	 * @return array<string, string>
	 */
	public function getJsRules(){
		return $this->jsRules;
	}
	
// 	/**
// 	* @Property
// 	* @param bool $required
// 	*/
// 	public setRequired($required){
// 		$this->required = $required;
// 	}
	
}