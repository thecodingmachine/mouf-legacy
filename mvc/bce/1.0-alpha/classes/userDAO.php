<?php
require_once 'DAOInterface.php';
require_once 'User.php';

/**
 * Dummy implementation of the User DAO
 * Left uncommented as it only uses for the example
 * @Component
 */
 class UserDAO implements DAOInterface{

	/**
	 * (non-PHPdoc)
	 * @see DAOInterface::getById()
	 */
	public function getById($id){
		$user = new user();
		$user->setId($id);
		$user->setName('user Name');
		$user->setEmail('user@test.com');
		$user->setRoleId(3);
		$user->setAge(20);
		
		return $user;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DAOInterface::getNew()
	 */
	public function getNew(){
		$user = new user();
		$user->setId(null);
		$user->setName('user Name');
		$user->setEmail('user@test.com');
		$user->setRoleId(3);
		$user->setAge(21);
		
		return $user;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DAOInterface::save()
	 */
	public function save($user){
		echo $user->getId() ? "UPDATE" : "CREATE";
		echo " user : ".var_export($user, true);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DAOInterface::getList()
	 */
	public function getList(){
		return null;
	}
	
}