<?php
/**
 * Just some dummy user object : id an name, email and role_id, that's it
 * Left uncommented as it only uses for the example
 */
 class User{
	
	public $id;
	public $name;
	public $email;
	public $roleId;
	
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getRoleId(){
		return $this->roleId;
	}
	
	public function setRoleId($roleId){
		$this->roleId = $roleId;
	}
	
	
}