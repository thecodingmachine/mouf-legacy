<?php
require_once 'BaseFieldDescriptor.php';

/**
 * This field descriptor can be used in order to handle foreing key relations of the Form's main bean.
 * For example, a user has a role_id, that references a role.id primary key...
 * 
 * Therefore, this class references
 * 		- a linked DAO that handles the related beans (example the role bean with id and label properties)
 * 		- a linkedFieldGetter name: the function of the linked bean that returns bean's id (and will set the main bean's foreign key)
 * 		- a linkedValueGetter name: the function of the linked bean that returns bean's value (or label).
 * 		  In our example, this would be the role labels the user might be affected
 * @Component
 */
class ForeignKeyFieldDescriptor extends BaseFieldDescriptor implements FieldDescriptorInterface{
	
	/**
	 * 
	 * Enter description here ...
	 * @Property
	 * @var DAOInterface
	 */
	public $dao;
	
	/**
	 * The name of the getter function of the linked bean's id
	 * @Property
	 * @var string
	 */
	public $linkedFieldGetter;
	
	/**
	* The name of the getter function of the linked bean's label
	* @Property
	* @var string
	*/
	public $linkedValueGetter;
	
	/**
	 * Associative array if ids and values
	 * @var array
	 */
	public $data;
	
	/**
	 * (non-PHPdoc)
	 * @see BaseFieldDescriptor::load()
	 */
	public function load($mainBean){
		parent::load($mainBean);
		
		$beanList = $this->dao->getList();
		foreach ($beanList as $bean) {
			$id = call_user_func(array($bean, $this->linkedFieldGetter));
			$value = call_user_func(array($bean, $this->linkedValueGetter));
			$data[$id] = $value;
		}
		$this->setData($data);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function setData($data){
		$this->data = $data;
	}
	
}