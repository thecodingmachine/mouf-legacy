<?php
require_once 'BaseFieldDescriptor.php';

/**
 * This field descriptor can be used in order to handle many to may urelations of the Form's main bean.
 * For example, a user has a set of hobbies, that are stored in a hobby table, and linked to the user by a user_hobby table
 * 
 * Therefore, this class references
 * 		- a linked DAO that handles the relation beans ('userhobby' bean)
 * @Component
 */
class Many2ManyFieldDescriptor extends BaseFieldDescriptor implements FieldDescriptorInterface{
	
	/**
	 * @Property
	 * @var DAOInterface
	 */
	public $dao;	
	
	/**
	 * Name of the method that returns the associative array of values
	 * @Property
	 * @var string
	 */
	public $dataMethod;
	
	/**
	 * Associative array if ids and values
	 * @var array
	 */
	private $data;
	
	/**
	 * (non-PHPdoc)
	 * @see BaseFieldDescriptor::load()
	 */
	public function load($mainBean){
		parent::load($mainBean);
		$this->data = call_user_func(array($this->dao, $this->dataMethod));
	}
	
	/**
	 * 
	 */
	public function getData(){
		return $this->data;
	}
	
}