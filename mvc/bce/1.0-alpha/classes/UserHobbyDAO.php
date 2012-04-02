<?php
require_once 'DAOInterface.php';
require_once 'UserHobby.php';

/**
 * Dummy implementation of the Role DAO
 * Left uncommented as it only uses for the example
 * @Component
 */
class UserHobbyDAO implements DAOInterface{

	public function getById($id){
		$user = new Hobby();
		$user->setId($id);
		$user->setLabel('Hobby label'.$id);
		return $user;
	}
	
	public function getNew(){
		$user = new Hobby();
		$user->setId(null);
		$user->setName('New Hobby');
		
		return $user;
	}
	
	public function save($userHobby){
		echo $hobby->getId() ? "UPDATE" : "CREATE";
		echo " Hobby : ".var_export($hobby, true);
	}
	
	public function getList(){
		for ($i = 0; $i < 7; $i++) {
			$hobbies[$i] = "Hobby $i";
		}
		
		return $hobbies;
	}
	
	public function getUserHobbbies($userId){
		return array(2,4);
	}
	
}