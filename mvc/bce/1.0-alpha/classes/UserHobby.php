<?php
/**
 * Just some dummy role object : id an label, that's it
 * Left uncommented as it only uses for the example
 */
class UserHobby{
	
	public $id;
	public $label;
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getLabel(){
		return $this->label;
	}
	
	public function setLabel($label){
		$this->label = $label;
	}
	
}