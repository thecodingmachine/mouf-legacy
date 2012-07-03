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
	public $mappingDao;
	
	/**
	 * @Property
	 * @var DAOInterface
	 */
	public $linkedDao;	
	
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
	 * Name of the method that returns the list of beans to be linked
	 * @Property
	 * @var string
	 */
	public $dataMethod;
	
	/**
	 * Name of the method that returns the beans of the mapping table that are already linked to the main bean
	 * @Property
	 * @var string
	 */
	public $beanValuesMethod;
	
	/**
	 * Name of the method that gets the Id of the mapping table
	 * @Property
	 * @var string
	 */
	public $mappingIdGetter;
	
	/**
	 * Name of the method that sets main bean's main bean's Id in mapping table (left column)
	 * @Property
	 * @var string
	 */
	public $mappingLeftKeySetter;
	
	/**
	 * Name of the method that gets linked bean's foreing key from mapping table (right column)
	 * @Property
	 * @var string
	 */
	public $mappingRightKeyGetter;
	
	/**
	 * Name of the method that sets linked bean's foreing key into mapping table (right column)
	 * @Property
	 * @var string
	 */
	public $mappingRightKeySetter;
	
	/**
	 * List of all beans available to be linked 
	 * @var array<mixed>
	 */
	private $data;
	
	/**
	 * List of all beans from the mapping table that are linked to the main bean
	 * @var array<mixed>
	 */
	private $beanValues;
	
	/**
	 * array of ids to be saved
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
		$this->beanValues = call_user_func(array($this->mappingDao, $this->beanValuesMethod), $mainBeanId);
	}
	
	/**
	 * Loads all available data
	 */
	public function loadData(){
		$this->data = call_user_func(array($this->linkedDao, $this->dataMethod));
	}
	
	/**
	 * Main bean Id setter (sets the foreign key value for main bean)
	 * @param mixed $id
	 */
	public function setMappingLeftKey($id, $bean){
		call_user_func(array($bean, $this->mappingLeftKeySetter), $id);
	}
	
	/**
	 * Linked bean Id setter (sets the foreign key value for main bean)
	 * @param mixed $id
	 */
	public function setMappingRightKey($id, $bean){
		call_user_func(array($bean, $this->mappingRightKeySetter), $id);
	}
	
	public function getMappingRightKey($bean){
		return call_user_func(array($bean, $this->mappingRightKeyGetter));
	}
	
	public function getMappingId($bean){
		return call_user_func(array($bean, $this->mappingIdGetter));
	}
	
	public function getRelatedBeanId($bean){
		return call_user_func(array($bean, $this->linkedIdGetter));
	}
	
	public function getRelatedBeanLabel($bean){
		return call_user_func(array($bean, $this->linkedLabelGetter));
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