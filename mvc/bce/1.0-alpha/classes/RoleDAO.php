<?php
require_once 'DAOInterface.php';
require_once 'Role.php';

/**
 * Dummy implementation of the Role DAO
 * Left uncommented as it only uses for the example
 * @Component
 */
class RoleDAO implements DAOInterface{

	public function getById($id){
		$user = new Role();
		$user->setId($id);
		$user->setLabel('role label '.$id);
		return $user;
	}
	
	public function getNew(){
		$user = new Role();
		$user->setId(null);
		$user->setName('New Role');
		
		return $user;
	}
	
	public function save($role){
		echo $user->getId() ? "UPDATE" : "CREATE";
		echo " user : ".var_export($user, true);
	}
	
	public function getList(){
		for ($i = 0; $i < 5; $i++) {
			$roles[] = $this->getById($i);
		}
		
		return $roles;
	}
	
}