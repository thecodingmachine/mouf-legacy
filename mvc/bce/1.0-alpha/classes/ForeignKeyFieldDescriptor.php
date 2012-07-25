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
class ForeignKeyFieldDescriptor extends BaseFieldDescriptor{
	
	/**
	 * 
	 * Enter description here ...
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
	 * Name of the method that get's the id of the linked Bean
	 * @Property
	 * @var string
	 */
	public $linkedIdGetter;
	
	/**
	 * Name of the method that get's the label of the linked Bean
	 * @Property
	 * @var string
	 */
	public $linkedLabelGetter;
	
	/**
	 * Associative array if ids and values
	 * @var array
	 */
	private $data;
	
	/**
	 * (non-PHPdoc)
	 * @see BaseFieldDescriptor::load()
	 */
	public function load($mainBean, $id = null, &$form = null){
		parent::load($mainBean, $id, $form);
		$this->data = call_user_func(array($this->dao, $this->dataMethod));
	}
	
	/**
	 * 
	 */
	public function getData(){
		return $this->data;
	}
	
	public function getRelatedBeanId($bean){
		return call_user_func(array($bean, $this->linkedIdGetter));
	}
	
	public function getRelatedBeanLabel($bean){
		return call_user_func(array($bean, $this->linkedLabelGetter));
	}
	
}