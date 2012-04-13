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
	
	
	/**
	 * @return int the messaged 
	 */
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}

	/**
	* @return string
	*/
	public function getEmail(){
		return $this->email;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	/**
	* @return int
	*/
	public function getRoleId(){
		return $this->roleId;
	}
	
	public function setRoleId($roleId){
		$this->roleId = $roleId;
	}
	
	/**
	* @return float
	*/
	public function getAge(){
		return $this->age;
	}
	
	public function setAge($age){
		$this->age = $age;
	}
	
	/**
	* @return timestamp
	*/
	public function getBirthDate(){
		return $this->brithDate;
	}
	
	public function setBirthDate($date){
		$this->brithDate = $date;
	}
	
	/**
	* @return string
	*/
	public function getWebSite(){
		return $this->webSite;
	}
	
	public function setWebSite($webSite){
		$this->webSite = $webSite;
	}
	
}