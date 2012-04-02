<?php
/**
 * Just some dummy user object : id an name, email and role_id, that's it
 * Left uncommented as it only uses for the example
 */
 class User{
	
	public $id;
	public $name;
	public $email;
	public $brithDate;
	public $age;
	public $roleId;
	public $webSite;
	public $hobbies;
	
	
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
	
	public function getAge(){
		return $this->age;
	}
	
	public function setAge($age){
		$this->age = $age;
	}
	
	public function getBirthDate(){
		return $this->brithDate;
	}
	
	public function setBirthDate($date){
		$this->brithDate = $date;
	}
	
	public function getWebSite(){
		return $this->webSite;
	}
	
	public function setWebSite($webSite){
		$this->webSite = $webSite;
	}
	
	public function getHobbies(){
		return $this->hobbies;
	}
	
	public function setHobbies($hobbies){
		$this->hobbies = $hobbies;
	}
}