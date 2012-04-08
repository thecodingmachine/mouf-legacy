<?php
/**
 * Just some dummy role object : id an label, that's it
 * Left uncommented as it only uses for the example
 */
class UserHobby{
	
	public $id;
	public $userId;
	public $hobbyId;
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	public function getHobbyId(){
		return $this->hobbyId;
	}
	
	public function setHobbyId($hobbyId){
		$this->hobbyId = $hobbyId;
	}
	
	
}