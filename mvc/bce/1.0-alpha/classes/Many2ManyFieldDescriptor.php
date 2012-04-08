<?php
/**
 * This field descriptor can be used in order to handle many to may urelations of the Form's main bean.
 * For example, a user has a set of hobbies, that are stored in a hobby table, and linked to the user by a user_hobby table
 * 
 * Therefore, this class references
 * 		- a linked DAO that handles the relation beans ('userhobby' bean)
 * @Component
 */
class Many2ManyFieldDescriptor extends FieldDescriptor{
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
	 * Name of the method that returns beans values
	 * @Property
	 * @var string
	 */
	public $beanValuesMethod;
	
	/**
	 * Name of the method that sets main bean's foreign key
	 * @Property
	 * @var string
	 */
	public $mainBeanIdSetter;
	
	/**
	 * Name of the method that sets linked bean's foreign key
	 * @Property
	 * @var string
	 */
	public $linkedBeanIdSetter;
	
	/**
	 * Associative array of ids and values
	 * @var array
	 */
	private $data;
	
	/**
	 * array of ids the bean is linked to (before performing save action)
	 * @var array<mixed>
	 */
	private $beanValues;
	
	/**
	 * array of ids (after save action)
	 * @var array<mixed>
	 */
	private $saveValues;
	
	/**
	 * Rewrite the function : load main bean's values and avalable ones
	 * @param mixed $mainBeanId the id of the main bean 
	 */
	public function load($mainBeanId){
		$this->loadValues($mainBeanId);
		$this->loadData();
	}
	
	/**
	 * Loads the values of the bean
	 * @param mixed $mainBeanId the id of the main bean 
	 */
	public function loadValues($mainBeanId){
		$this->beanValues = call_user_func(array($this->dao, $this->beanValuesMethod), $mainBeanId);
	}
	
	/**
	 * Loads all available data
	 */
	public function loadData(){
		$this->data = call_user_func(array($this->dao, $this->dataMethod));
	}
	
	/**
	 * Main bean Id setter (sets the foreign key value for main bean)
	 * @param mixed $id
	 */
	public function setMainId($id, $bean){
		call_user_func(array($bean, $this->mainBeanIdSetter), $id);
	}
	
	/**
	 * Linked bean Id setter (sets the foreign key value for main bean)
	 * @param mixed $id
	 */
	public function setLinkedId($id, $bean){
		call_user_func(array($bean, $this->linkedBeanIdSetter), $id);
	}
	
	/**
	 * Sets the values to be saved during validation process.
	 * If everything went well, thses values will be used to perform saves and deletes
	 * @param mixed $values: the values to be set
	 */
	public function setSaveValues($values){
		$this->saveValues = $values;
	}
	
	/**
	 * Returns the values avalable for the field
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * Returns the values already set for the field
	 */
	public function getBeanValues(){
		return $this->beanValues;
	}
	
	/**
	 * Returns the values to be set for the field
	 */
	public function getSaveValues(){
		return $this->saveValues;
	}
	
}