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
		$userHobby = new UserHobby();
		$userHobby->setId($id);
		$userHobby->setLabel('Hobby label'.$id);
		return $userHobby;
	}
	
	public function getNew(){
		$userHobby = new UserHobby();
		$userHobby->setId(null);
		return $userHobby;
	}
	
	public function save($userHobby){
		echo $userHobby->getId() ? "UPDATE" : "CREATE";
		echo " Hobby : ".var_export($userHobby, true);
	}
	
	public function deleteByForeignKeys($userId, $hobbyId){
		echo "DELETE where :: userId : $userId - Hobby id: $hobbyId<br/>";
	}
	
	public function getList(){
		for ($i = 1; $i < 7; $i++) {
			$userHobbies[$i] = "Hobby $i";
		}
		return $userHobbies;
	}
	
	public function getUserHobbbies($userId){
		return array(2,4);
	}
	
	
	
}